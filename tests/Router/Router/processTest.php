<?php
namespace MultiRouting\Router;

use IoC\Container;

function header($value)
{
    Container::getInstance()->resolve('internal.mock')->header($value);
}

namespace tests\Router\Router;

use IoC\Container;
use MultiRouting\Router\Dispatchers\RestDispatcher;
use MultiRouting\Router\Request;
use MultiRouting\Router\Response;
use MultiRouting\Router\Router;

class processTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the call is made from a group then add the routes.
     * @note this is a system test.
     */
    public function testWhenTheCallIsMadeFromAGroupThenAddTheRoutes()
    {
        $response1 = \Mockery::mock('\MultiRouting\Router\Response')->makePartial();
        $response2 = \Mockery::mock('\MultiRouting\Router\Response')->makePartial();

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        // bootstrap
        $router = \Mockery::mock('\MultiRouting\Router\Router[beforeFilter,dispatchToRoute,prepareResponse]');
        $router->shouldAllowMockingProtectedMethods();
        $router->shouldReceive('beforeFilter')->once()->with($request);
        $router->shouldReceive('dispatchToRoute')->once()->with($request)->andReturn($response1);
        $router->shouldReceive('prepareResponse')->once()->with($request, $response1)->andReturn($response2);

        Container::getInstance()->register('Router\Router', $router);

        // action
        Router::group(['serialization' => 'rest', 'middleware' => ['NoneShallPass']], function () {
            Router::get('/orders/', '/action-list-orders/', []);
            Router::get('/order/{refNo}/', '/get-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::post('/order/{refNo}/', '/post-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::put('/order/{refNo}/', '/put-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::delete('/order/{refNo}/', '/delete-order/{refNo}/', ['refNo' => '[\d]+']);
        });

        /** @var Router $router */
        $response = $router->process($request);
        static::assertSame($response2, $response); /** @note something is strange here. test for soap file contents */
    }

    /**
     * When matches are found then return response.
     * @note system test
     */
    public function testWhenMatchesAreFoundThenReturnResponse()
    {
        // prepare router and dispatcher
        $router = new Router();
        Container::getInstance()->register('Router\Router', $router);

        $controller = \Mockery::mock(__NAMESPACE__ . '\FakeControllerUnderTestProcess')->makePartial();
        $controller->shouldReceive('saveProduct')->passthru();
        Container::getInstance()->register('\App\Controllers\ProductDetails', $controller);

        Container::getInstance()->register('Router\Dispatcher', function ($serialization) {
            if ($serialization === 'rest') {
                return new RestDispatcher();
            }
            throw new \Exception('Wrong dispatcher or not set.');
        });

        Container::getInstance()->register('Router\Request\Interpreter', function ($serialization) {
            if ($serialization === 'rest') {
                return new Request\Interpreters\RestInterpreter();
            }
            throw new \Exception('Wrong interpreter or not set.');
        });

        Container::getInstance()->register('Router\Request\Handler', function ($serialization, $controller) {
            if ($serialization === 'rest') {
                return new Request\Handlers\RestHandler($controller);
            }
            throw new \Exception('Wrong interpreter or not set.');
        });

        /** @note action pattern is {class-or-namespaced-class}@{method}. Document or add a restriction / checker in the Route's constructor. */

        // prepare routes
        Router::group(['serialization' => 'rest', 'middleware' => ['Filter1']], function () {
            Router::get('/products/', '\App\Controllers\ProductsListing@listProducts', []);
            Router::post('/products/', '\App\Controllers\ProductsListing@addProduct', ['productId' => '[\d]+']);
            Router::get('/product/{productId}/', '\App\Controllers\ProductsListing@getProduct', ['productId' => '[\d]+']);
            Router::put('/product/{productId}/', '\App\Controllers\ProductDetails@saveProduct', ['productId' => '[\d]+']);
            Router::delete('/product/{productId}/', '\App\Controllers\ProductDetails@deleteProduct', ['productId' => '[\d]+']);
        });

        // prepare request
        $request = new Request(
            [
                'returnBody' => 'false'
            ],
            [
                'Hash' => 'aaabbbccc',
                'ProductId' => 100001,
                'Version' => '1.0.0',
            ],
            [],
            [],
            [
                'REQUEST_METHOD' => 'put',
                'SCRIPT_URL' => '/rest/1.1/product/100001/',
            ],
            'content-sample'
        );

        // set middleware expectations
        $filter = \Mockery::mock('FakeMiddleware')->makePartial();

        /** @note do foreach on all added routes to have more control on expectations */
        $filter->shouldReceive('handle')->once()->andReturnNull();

        Container::getInstance()->register('Middleware\Filter1', $filter);

        // action
        $response = $router->process($request);
        $expected = new Response(json_encode(['result' => json_encode(['100001'])]));

        static::assertEquals($expected, $response);
    }

    /**
     * When the request has a wsdl query parameter then return the wsdl file contents as a response.
     */
    public function testWhenTheRequestHasAWsdlQueryParameterThenReturnTheWsdlFileContentsAsAResponse()
    {
        // prepare router and dispatcher
        $router = new Router();
        Container::getInstance()->register('Router\Router', $router);

        // the wsdl file will load and text/xml ContentType headers set.
        $internalMock = \Mockery::mock('fakeInternal')->makePartial();
        $internalMock->shouldReceive('header')->with('Content-type: text/xml');
        Container::getInstance()->register('internal.mock', $internalMock);

        $controller = \Mockery::mock(__NAMESPACE__ . '\FakeControllerUnderTestProcess')->makePartial();
        $controller->shouldReceive('saveProduct')->passthru();
        Container::getInstance()->register('\App\Controllers\ProductDetails', $controller);

        Container::getInstance()->register('Router\Dispatcher', function ($serialization) {
            if ($serialization === 'soap') {
                return new RestDispatcher();
            }
            throw new \Exception('Wrong dispatcher or not set.');
        });

        Container::getInstance()->register('Router\Request\Interpreter', function ($serialization) {
            if ($serialization === 'soap') {
                return new Request\Interpreters\RestInterpreter();
            }
            throw new \Exception('Wrong interpreter or not set.');
        });

        Container::getInstance()->register('Router\Request\Handler', function ($serialization, $controller) {
            if ($serialization === 'soap') {
                return new Request\Handlers\RestHandler($controller);
            }
            throw new \Exception('Wrong interpreter or not set.');
        });

        /** @note action pattern is {class-or-namespaced-class}@{method}. Document or add a soapriction / checker in the Route's constructor. */

        // prepare routes
        $matcher = [
            'productId' => '[\d]+',
            'version' => '[\w]+',
        ];

        Router::group(['serialization' => 'soap', 'middleware' => ['Filter1']], function () use ($matcher) {
            Router::post('/', '\App\Controllers\ProductsListing@listProducts', $matcher);
            Router::post('/', '\App\Controllers\ProductsListing@addProduct', $matcher);
        });

        // prepare request
        $request = new Request(
            [
                'wsdl' => '' /** @note "/someurl/?wsdl" may lead to false positives. document this */
            ],
            [
                'Hash' => 'aaabbbccc',
                'ProductId' => 100001,
                'Version' => '1.0.0',
            ],
            [],
            [],
            [
                'REQUEST_METHOD' => 'post',
                /** @note resourceUrl from the request is not matched with the routes */
                'SCRIPT_URL' => '/soap/1.1/some-invalid-url-not-matching-the-routes/',
            ],
            'content-sample'
        );

        // action
        $response = $router->process($request);
        $expected = new Response(file_get_contents(\WSDL_PATH));

        static::assertEquals($expected, $response);
    }
}

class FakeControllerUnderTestProcess
{
    public function saveProduct($productId)
    {
        return json_encode(func_get_args());
    }
}