<?php
namespace tests\Router\Router;

use IoC\Container;
use MultiRouting\Router\Route;
use MultiRouting\Router\RouteCollection;
use MultiRouting\Router\Router;

/**
 */
class getTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will add a get route using the serialization and middleware.
     */
    public function testWillAddAGetRouteUsingTheSerializationAndMiddleware()
    {
        $intent = '/list-order/{refNo}/';
        $action = '/order/{refNo}/';
        $matching = [
            'refNo' => '[\d]+'
        ];

        $inceptionRouter = \Mockery::mock('\MultiRouting\Router\Router')->makePartial();
        $inceptionRouter::setMiddleware(['CheckSomething']); // static values
        $inceptionRouter->shouldReceive('addRoute')->once()
            ->with(Router::DEFAULT_SERIALIZATION, 'get', $intent, $action, $matching, ['CheckSomething']);
        /** @note check using Mockery's method call asserts */

        Container::getInstance()->register('Router\Router', $inceptionRouter);

        // call action
        Router::get($intent, $action, $matching);
    }

    /**
     * When the call is made from a group then add the routes.
     * @note this is a system test.
     */
    public function testWhenTheCallIsMadeFromAGroupThenAddTheRoutes()
    {
        // bootstrap
        $inceptionRouter = new RouterUnderTestGet();
        Container::getInstance()->register('Router\Router', $inceptionRouter);

        // action
        Router::group(['serialization' => 'rest', 'middleware' => ['NoneShallPass']], function () {
            Router::get('/orders/', '/action-list-orders/', []);
            Router::get('/order/{refNo}/', '/get-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::post('/order/{refNo}/', '/post-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::put('/order/{refNo}/', '/put-order/{refNo}/', ['refNo' => '[\d]+']);
            Router::delete('/order/{refNo}/', '/delete-order/{refNo}/', ['refNo' => '[\d]+']);
        });

        $router = Container::getInstance()->resolve('Router\Router');

        /** @var RouteCollection $routes */
        $routeCollection = $router->routes;
        // static::assertEquals(2, $routeCollection->count());

        // check get routes and actions
        $routes = $routeCollection->getRoutes();
        $expected = [
            'get' => [
                '/orders/' => [
                    'rest' => new Route('rest', 'get', '/orders/', '/action-list-orders/', [], ['NoneShallPass']),
                ],
                '/order/{refNo}/' => [
                    'rest' => new Route('rest', 'get', '/order/{refNo}/', '/get-order/{refNo}/', ['refNo' => '[\d]+'], ['NoneShallPass']),
                ],
            ],
            'post' => [
                '/order/{refNo}/' => [
                    'rest' => new Route('rest', 'post', '/order/{refNo}/', '/post-order/{refNo}/', ['refNo' => '[\d]+'], ['NoneShallPass']),
                ],
            ],
            'put' => [
                '/order/{refNo}/' => [
                    'rest' => new Route('rest', 'put', '/order/{refNo}/', '/put-order/{refNo}/', ['refNo' => '[\d]+'], ['NoneShallPass']),
                ],
            ],
            'delete' => [
                '/order/{refNo}/' => [
                    'rest' => new Route('rest', 'delete', '/order/{refNo}/', '/delete-order/{refNo}/', ['refNo' => '[\d]+'], ['NoneShallPass']),
                ],
            ],
        ];

        static::assertEquals($expected, $routes);
    }
}

class RouterUnderTestGet extends Router
{
    public $routes;
}