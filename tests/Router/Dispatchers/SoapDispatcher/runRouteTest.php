<?php
namespace tests\Router\Dispatchers\SoapDispatcher;


use MultiRouting\Router\Dispatchers\SoapDispatcher;

class createServerTest extends \PHPUnit_Framework_TestCase
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
        $action = 'action-value';
        $controller = 'controller-value';

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getAction')->once()->andReturn($action);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        /** @note prepare handler */
        $handler = \Mockery::mock('fakeHandler')->makePartial();

        $soapServer = \Mockery::mock('SoapServer')->makePartial();
        $soapServer->shouldReceive('setObject')->once()->with($handler);
        $soapServer->shouldReceive('handle')->once();

        $object = \Mockery::mock('\tests\Router\Dispatchers\SoapDispatcher\SoapDispatcherUnderTestRunRoute')->makePartial();
        $object->shouldAllowMockingProtectedMethods();
        $object->shouldReceive('getController')->once()->with($action)->andReturn($controller);
        $object->shouldReceive('createServer')->once()->andReturn($soapServer); // ->with(\WSDL_PATH, ['cache_wsdl', 0])

        /** @note match stored handler */
        \IoC\Container::getInstance()->register('Router\Request\Handler', function ($serialization, $controllerInput) use ($controller, $handler) {
            if ($serialization === 'soap' && $controller === $controllerInput) {
                return $handler;
            }
        });

        static::assertNull($object->runRoute($route, $request));
    }
}

class SoapDispatcherUnderTestRunRoute extends SoapDispatcher
{
    public function runRoute($route, $request)
    {
        return parent::runRoute($route, $request);
    }
}
