<?php
namespace tests\Router\Request\Interpreters\RpcInterpreter;


use IoC\Container;
use MultiRouting\Router\Intent;
use MultiRouting\Router\Request\Interpreters\RpcInterpreter;

class getIntentTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will return an intent with the called method.
     */
    public function testWillReturnAnIntentWithTheCalledMethod()
    {
        $requestContent = 'request-content';
        $requestMethod = 'getCustomer';

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getContent')->once()->andReturn($requestContent);

        $instance = \Mockery::mock('\MultiRouting\Helpers\JsonParser')->makePartial();
        $instance->shouldReceive('setRequest')->once()->with($requestContent);
        $instance->shouldReceive('getCalledMethod')->once()->andReturn($requestMethod);

        Container::getInstance()->register('Helpers\JsonParser', $instance);

        $object = new RpcInterpreter();
        $response = $object->getIntent($request);

        static::assertEquals(new Intent($requestMethod), $response);
    }
}
