<?php
namespace tests\Router\Router;


use MultiRouting\Router\Route;
use MultiRouting\Router\RouteCollection;
use MultiRouting\Router\Router;

class addRouteTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will add a new route to the route list.
     */
    public function testWillAddANewRouteToTheRouteList()
    {
        $router = new Router();
        $helper = new \ProtectedHelper($router);

        $serialization = 'rest';
        $httpMethod = 'get';
        $intent = '/orders/';
        $action = 'list'; // @note what is the action for?
        $matching = [];
        $middleware = [];

        // prepare
        $initialRouteList = new RouteCollection();
        $initialRouteList->add(new Route($serialization, 'post', $intent, $action, $matching, $middleware));
        $initialRouteList->add(new Route($serialization, 'post', '/currencies/', $action, $matching, $middleware));
        $helper->setValue('routes', $initialRouteList);

        // add
        $newRoute = new Route($serialization, $httpMethod, $intent, $action, $matching, $middleware);

        $expected = new RouteCollection();
        $expected->add($newRoute);
        $expected->add(new Route($serialization, 'post', $intent, $action, $matching, $middleware));
        $expected->add(new Route($serialization, 'post', '/currencies/', $action, $matching, $middleware));

        $response = $router->addRoute($serialization, $httpMethod, $intent, $action, $matching, $middleware);
        static::assertEquals($newRoute, $response); // will return the created route
        static::assertEquals($expected, $helper->getValue('routes')); // will store the route
    }
}
