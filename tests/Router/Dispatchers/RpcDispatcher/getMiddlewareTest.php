<?php
namespace tests\Router\Dispatchers\RpcDispatcher;


use MultiRouting\Router\Dispatchers\RpcDispatcher;

class getMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the class is not set in the dependency container then throw exception.
     */
    public function testWhenTheClassIsNotSetInTheDependencyContainerThenThrowException()
    {
        $object = new RpcDispatcher();
        $helper = new \ProtectedHelper($object);

        try {
            $helper->call('getMiddleware', ['\Middleware\InvalidMiddleware']);

        } catch (\Exception $e) {
            static::assertEquals('Middleware Class Not Found: \Middleware\InvalidMiddleware', $e->getMessage());
            static::assertEquals(0, $e->getCode());
        }
    }

    /**
     * When the class is set in the dependency container then return instance.
     */
    public function testWhenTheClassIsSetInTheDependencyContainerThenReturnInstance()
    {
        $expected = new \stdClass();
        \IoC\Container::getInstance()->register('\Middleware\ValidMiddleware', $expected);

        $object = new RpcDispatcher();
        $helper = new \ProtectedHelper($object);

        $instance = $helper->call('getMiddleware', ['\Middleware\ValidMiddleware']);
        static::assertSame($expected, $instance);
    }
}
