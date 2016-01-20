<?php
namespace tests\Router\RouteCollection;


use MultiRouting\Router\RouteCollection;

class getRoutesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When no results are found for the given httpmethod then throw exception.
     */
    public function testWhenNoResultsAreFoundForTheGivenHttpmethodThenThrowException()
    {
        $routeCollection = new RouteCollection();
        $routeGet = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $routePut = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

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
            $routeCollection->getRoutes('post');
            static::fail('Expected an exception.');

        } catch (\Exception $e) {
            static::assertEquals('Routes not found: post', $e->getMessage());
        }
    }

    /**
     * When no httpmethod is given then return all routes.
     * @note should also check if routes are found (empty array) or if routes are Route[][][] before return.
     */
    public function testWhenNoHttpmethodIsGivenThenReturnAllRoutes()
    {
        $routeCollection = new RouteCollection();
        $routeGet = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $routePut = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $routes = [
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
        ];

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', $routes);

        static::assertSame($routes, $routeCollection->getRoutes());
    }

    /**
     * When httpmethod is given and results are found then return matched routes.
     * @note should also check if response is Route[].
     */
    public function testWhenHttpmethodIsGivenAndResultsAreFoundThenReturnMatchedRoutes()
    {
        $routeCollection = new RouteCollection();
        $routeGet = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $routePut = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $routes = [
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
        ];

        $helper = new \ProtectedHelper($routeCollection);
        $helper->setValue('routes', $routes);

        static::assertSame($routes['put'], $routeCollection->getRoutes('put'));
    }
}
