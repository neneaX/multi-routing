<?php
namespace tests\Router\Matching\MethodValidator;


use MultiRouting\Router\Matching\MethodValidator;

class matchesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Checks if the route and the request have the same method name and return true if so.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameMethodNameAndReturnTrueIfSo()
    {
        $methodName = 'sameMethodName';

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getHttpMethod')->once()->andReturn($methodName);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getMethod')->once()->andReturn($methodName);

        $validator = new MethodValidator();
        static::assertTrue($validator->matches($route, $request));
    }

    /**
     * Checks if the route and the request have the same method name and return false if not.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameMethodNameAndReturnFalseIfNot()
    {
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getHttpMethod')->once()->andReturn('getOrder');

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getMethod')->once()->andReturn('getProduct');

        $validator = new MethodValidator();
        static::assertFalse($validator->matches($route, $request));
    }
}
