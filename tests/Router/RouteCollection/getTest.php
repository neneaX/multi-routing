<?php
namespace tests\Router\RouteCollection;


use MultiRouting\Router\RouteCollection;

class getTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When no results are found then throw exception.
     */
    public function testWhenNoResultsAreFoundThenThrowException()
    {
        $routeCollection = new RouteCollection();
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', [
            'get' => [
                '/orders/' => [
                    'rest' => $route
                ]
            ]
        ]);

        try {
            $routeCollection->get('get', '/orders/', 'soap');
            static::fail('Expected an exception.');

        } catch (\Exception $e) {
            static::assertEquals('Route not found: get /orders/ soap', $e->getMessage());
        }
    }

    /**
     * When route is found then return route.
     */
    public function testWhenRouteIsFoundThenReturnRoute()
    {
        $routeCollection = new RouteCollection();
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', [
            'get' => [
                '/orders/' => [
                    'rest' => $route
                ]
            ]
        ]);

        $response = $routeCollection->get('get', '/orders/', 'rest');
        static::assertSame($route, $response);
    }
}
