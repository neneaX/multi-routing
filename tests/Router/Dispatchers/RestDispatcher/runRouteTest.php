<?php
namespace tests\Router\Dispatchers\RestDispatcher;


use MultiRouting\Router\Dispatchers\RestDispatcher;

class runRouteTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will run middleware and return run route response.
     */
    public function testWillRunMiddlewareAndReturnRunRouteResponse()
    {
        $expected = 'output response final';

        $expectedArray = [
            'a' => 'b',
            'c' => 'd',
        ];

        $parametersMock = \Mockery::mock('\MultiRoute\Router\Parameter')->makePartial();
        $parametersMock->shouldReceive('toArray')->andReturn($expectedArray);

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        $action = 'action-value';
        $controller = 'controller-value';
        $method = 'method-value';

        /** @note prepare handler */
        $handler = \Mockery::mock('fakeHandler')->makePartial();
        $handler->shouldReceive($method)->once()->with('b', 'd')->andReturn($expected);

        $route->shouldReceive('getAction')->once()->andReturn($action);
        $route->shouldReceive('getParameters')->once()->andReturn($parametersMock);

        $object = \Mockery::mock('\tests\Router\Dispatchers\RestDispatcher\RestDispatcherUnderTestRunRoute')->makePartial();
        $object->shouldAllowMockingProtectedMethods();
        $object->shouldReceive('getController')->once()->with($action)->andReturn($controller);
        $object->shouldReceive('getMethod')->once()->with($action)->andReturn($method);

        /** @note match stored handler */
        \IoC\Container::getInstance()->register('Router\Request\Handler', function ($serialization, $controllerInput) use ($controller, $handler) {
            if ($serialization === 'rest' && $controller === $controllerInput) {
                return $handler;
            }
        });

        static::assertEquals($expected, $object->runRoute($route, $request));
    }
}

class RestDispatcherUnderTestRunRoute extends RestDispatcher
{
    public function runRoute($route, $request)
    {
        return parent::runRoute($route, $request);
    }
}
