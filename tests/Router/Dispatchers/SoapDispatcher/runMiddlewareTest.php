<?php
namespace tests\Router\Dispatchers\SoapDispatcher;


use MultiRouting\Router\Dispatchers\SoapDispatcher;

/**
 * @note Hard couple for namespace prefix "Middleware".
 */
class runMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When no routes are set then return null.
     */
    public function testWhenNoRoutesAreSetThenReturnNull()
    {
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        $route->shouldReceive('getMiddleware')->once()->andReturn([]);

        $object = new SoapDispatcher();
        $helper = new \ProtectedHelper($object);

        static::assertNull($helper->call('runMiddleware', [$route, $request]));
    }

    /**
     * When routes are set then run through all of them.
     */
    public function testWhenRoutesAreSetThenRunThroughAllOfThem()
    {
        $route = \Mockery::mock('\\MultiRouting\\Router\\Route')->makePartial();
        $request = \Mockery::mock('\\MultiRouting\\Router\\Request')->makePartial();

        $middlewareOneMock = \Mockery::mock('fakeMiddleware1')->makePartial();
        $middlewareTwoMock = \Mockery::mock('fakeMiddleware2')->makePartial();

        $middlewareOneMock->shouldReceive('handle')->once()->with($route, $request);
        $middlewareTwoMock->shouldReceive('handle')->once()->with($route, $request);

        $route->shouldReceive('getMiddleware')->once()->andReturn(['ClassOne', 'ClassTwo']);

        $object = \Mockery::mock(__NAMESPACE__ . '\SoapDispatcherUnderTestRunMiddleware')->makePartial();
        $object->shouldAllowMockingProtectedMethods();
        $object->shouldReceive('getMiddleware')->once()->with('Middleware\ClassOne')->andReturn($middlewareOneMock);
        $object->shouldReceive('getMiddleware')->once()->with('Middleware\ClassTwo')->andReturn($middlewareTwoMock);

        static::assertNull($object->runMiddleware($route, $request));
    }

    /**
     * When a middleware is not found then throw exception.
     */
    public function testWhenAMiddlewareIsNotFoundThenThrowException()
    {
        $route = \Mockery::mock('\\MultiRouting\\Router\\Route')->makePartial();
        $request = \Mockery::mock('\\MultiRouting\\Router\\Request')->makePartial();

        $route->shouldReceive('getMiddleware')->once()->andReturn(['ClassOne', 'ClassTwo', 'ClassThree']);

        $object = \Mockery::mock(__NAMESPACE__ . '\SoapDispatcherUnderTestRunMiddleware')->makePartial();

        try {
            $object->runMiddleware($route, $request);
            static::fail('Expecting exception.');

        } catch (\Exception $e) {
            static::assertEquals('Invalid Middleware: Middleware Class Not Found: Middleware\ClassOne', $e->getMessage());
        }
    }

    /**
     * When a middleware is found but can't handle then throw exception.
     */
    public function testWhenAMiddlewareIsFoundButCanTHandleThenThrowException()
    {
        $exception = new \Exception('message-cargo');

        $route = \Mockery::mock('\\MultiRouting\\Router\\Route')->makePartial();
        $request = \Mockery::mock('\\MultiRouting\\Router\\Request')->makePartial();

        $middlewareOneMock = \Mockery::mock('fakeMiddleware1')->makePartial();
        $middlewareTwoMock = \Mockery::mock('fakeMiddleware2')->makePartial();

        $middlewareOneMock->shouldReceive('handle')->once()->with($route, $request);
        $middlewareTwoMock->shouldReceive('handle')->once()->with($route, $request)->andThrow($exception);

        $route->shouldReceive('getMiddleware')->once()->andReturn(['ClassOne', 'ClassTwo']);

        $object = \Mockery::mock(__NAMESPACE__ . '\SoapDispatcherUnderTestRunMiddleware')->makePartial();
        $object->shouldAllowMockingProtectedMethods();
        $object->shouldReceive('getMiddleware')->once()->with('Middleware\ClassOne')->andReturn($middlewareOneMock);
        $object->shouldReceive('getMiddleware')->once()->with('Middleware\ClassTwo')->andReturn($middlewareTwoMock);

        try {
            $object->runMiddleware($route, $request);
            static::fail('Expecting exception.');

        } catch (\Exception $e) {
            static::assertEquals('Request could not be handled by Middleware Middleware\ClassTwo: message-cargo', $e->getMessage());
        }
    }
}

class SoapDispatcherUnderTestRunMiddleware extends SoapDispatcher
{
    public function runMiddleware($route, $request)
    {
        parent::runMiddleware($route, $request);
    }
}