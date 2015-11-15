<?php
namespace tests\Router\RouteCollection;


use MultiRouting\Router\RouteCollection;

class matchTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Use the request to get the routes for the httpmethod to get the matching route's binding response.
     */
    public function testUseTheRequestToGetTheRoutesForTheHttpmethodToGetTheMatchingRouteSBindingResponse()
    {
        $routeCollection = new RouteCollection();

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getMethod')->once()->andReturn('put');

        $routeGet = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $routePut = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $routePut->shouldReceive('matches')->once()->with($request)->andReturn(true);
        $routePut->shouldReceive('bind')->once()->with($request)->andReturn('[put /orders/ rest] matched with request - test response');

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', [
            'get' => [
                '/orders/' => [
                    'rest' => $routeGet
                ]
            ],
            'put' => [
                '/orders/' => [
                    'rest' => $routePut
                ]
            ],
        ]);

        static::assertEquals('[put /orders/ rest] matched with request - test response', $routeCollection->match($request));
    }

    /**
     * When no route is matched for the request then throw exception.
     */
    public function testWhenNoRouteIsMatchedForTheRequestThenThrowException()
    {
        $routeCollection = new RouteCollection();

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getMethod')->once()->andReturn('put');

        $routeGet = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $routePut = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $routePut->shouldReceive('matches')->once()->with($request)->andReturn(false);

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', [
            'get' => [
                '/orders/' => [
                    'rest' => $routeGet
                ]
            ],
            'put' => [
                '/orders/' => [
                    'rest' => $routePut
                ]
            ],
        ]);

        try {
            $routeCollection->match($request);
            static::fail();

        } catch (\Exception $e) {
            static::assertEquals('Matched route not found.', $e->getMessage());
        }
    }

    /**
     * When no routes are set for the request's httpmethod then throw exception.
     */
    public function testWhenNoRoutesAreSetForTheRequestSHttpmethodThenThrowException()
    {
        $routeCollection = \Mockery::mock('\MultiRouting\Router\RouteCollection')->makePartial();
        $routeCollection->shouldAllowMockingProtectedMethods();
        $routeCollection->shouldReceive('getRoutes')->with('post')->andReturn([]);
        $routeCollection->shouldReceive('match')->once()->passthru();

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getMethod')->once()->andReturn('post');

        try {
            $routeCollection->match($request);
            static::fail();

        } catch (\Exception $e) {
            static::assertEquals('Matched route not found.', $e->getMessage());
        }
    }

}
