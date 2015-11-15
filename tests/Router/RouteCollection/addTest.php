<?php
namespace tests\Router\RouteCollection;


use MultiRouting\Router\RouteCollection;

class addTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * By default no routes are added without calling the add method.
     */
    public function testByDefaultNoRoutesAreAddedWithoutCallingTheAddMethod()
    {
        $routeCollection = new RouteCollection();

        $helper = new \ProtectedHelper($routeCollection);
        static::assertEquals([], $helper->getValue('routes'));
    }

    /**
     * The route is stored using details about the httpmethod, intent and serialization as keys.
     */
    public function testTheRouteIsStoredUsingDetailsAboutTheHttpmethodIntentAndSerializationAsKeys()
    {
        $routeCollection = new RouteCollection();

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getHttpMethod')->once()->andReturn('get');
        $route->shouldReceive('getIntent')->once()->andReturn('/orders/');
        $route->shouldReceive('getSerialization')->once()->andReturn('rest');

        $routeCollection->add($route);

        $expected = [
            'get' => [
                '/orders/' => [
                    'rest' => $route
                ]
            ]
        ];

        $helper = new \ProtectedHelper($routeCollection);
        static::assertEquals($expected, $helper->getValue('routes'));
    }
}
