<?php
namespace tests\Router\Route;


use IoC\Container;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Route;

class bindTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Bind has a fluent interface.
     */
    public function testBindHasAFluentInterface()
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

        $sessionId = '00224466';
        $parameters = new ParameterCollection([]);

        $route = new Route($serialization, $httpMethod, $intent, $action, $matching, $middleware);

        $urlMock = \Mockery::mock('\MultiRouting\Router\Url')->makePartial();
        $urlMock->shouldReceive('getSerialization')->once()->andReturn($serialization);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $restInterpreter = \Mockery::mock('\MultiRouting\Router\Request\Interpreters\RestInterpreter')->makePartial();
        $restInterpreter->shouldReceive('getParameters')->once()->with($route, $request)->andReturn($parameters);
        $restInterpreter->shouldReceive('getSessionId')->once()->with($request)->andReturn($sessionId);

        Container::getInstance()->register('Router\Request\Interpreter', function ($serialization) use ($restInterpreter) {
            if ($serialization === 'rest') {
                return $restInterpreter;
            }
        });

        $response = $route->bind($request);

        static::assertSame($route, $response);
        static::assertSame($sessionId, $route->getSessionId());
        static::assertSame($parameters, $route->getParameters());
    }
}
