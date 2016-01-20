<?php
namespace tests\Router\Matching\SerializationValidator;


use MultiRouting\Router\Matching\SerializationValidator;

class matchesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Checks if the route and the request have the same serialization and return true if so.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameSerializationAndReturnTrueIfSo()
    {
        $urlMock = \Mockery::mock('\MultiRouting\Router\Url')->makePartial();
        $urlMock->shouldReceive('getSerialization')->once()->andReturn('soap');

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getSerialization')->once()->andReturn('soap');

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $validator = new SerializationValidator();
        static::assertTrue($validator->matches($route, $request));
    }

    /**
     * Checks if the route and the request have the same method name and return false if not.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameMethodNameAndReturnFalseIfNot()
    {
        $urlMock = \Mockery::mock('\MultiRouting\Router\Url')->makePartial();
        $urlMock->shouldReceive('getSerialization')->once()->andReturn('soap');

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getSerialization')->once()->andReturn('rest');

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $validator = new SerializationValidator();
        static::assertFalse($validator->matches($route, $request));
    }
}
