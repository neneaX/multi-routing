<?php
namespace tests\Router\Route;


use MultiRouting\Router\Route;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will store the the input values.
     */
    public function testWillStoreTheTheInputValues()
    {
        $serialization = 'rest';

        $httpMethod = 'get';

        $intent = '/orders/{refNo}/';

        $action = '/orders/123/';

        $matching = [
            'refNo' => '[\d]+'
        ];

        $middleware = [
            'ClassOne',
            'ClassTwo',
        ];

        $route = new Route($serialization, $httpMethod, $intent, $action, $matching, $middleware);

        static::assertEquals($serialization, $route->getSerialization());
        static::assertEquals($httpMethod, $route->getHttpMethod());
        static::assertEquals($action, $route->getAction());
        static::assertEquals($intent, $route->getIntent());
        static::assertEquals($matching, $route->getMatching());
        static::assertEquals($middleware, $route->getMiddleware());
    }
}
