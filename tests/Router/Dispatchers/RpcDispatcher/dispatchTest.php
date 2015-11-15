<?php
namespace tests\Router\Dispatchers\RpcDispatcher;


class dispatchTest extends \PHPUnit_Framework_TestCase
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
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        $expected = 'route response';

        $object = \Mockery::mock('\MultiRouting\Router\Dispatchers\RpcDispatcher')->makePartial();
        $object->shouldAllowMockingProtectedMethods();
        $object->shouldReceive('runMiddleware')->once()->with($route, $request);
        $object->shouldReceive('runRoute')->once()->with($route, $request)->andReturn($expected);

        $response = $object->dispatch($route, $request);
        static::assertEquals($expected, $response);
    }
}
