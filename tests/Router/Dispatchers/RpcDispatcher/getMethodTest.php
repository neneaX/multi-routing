<?php
namespace tests\Router\Dispatchers\RpcDispatcher;


use MultiRouting\Router\Dispatchers\RpcDispatcher;

class getMethodTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When method is set in the stored container then return controller object.
     */
    public function testWhenMethodIsSetInTheStoredContainerThenReturnControllerObject()
    {
        $label = '\Rpc\Controller\MainController';

        \IoC\Container::getInstance()->register($label, function ($serialization) {
            if ($serialization == 'rpc') {
                return new FakeControllerUnderTestGetMethod();
            }
        });

        $object = new RpcDispatcher();
        $helper = new \ProtectedHelper($object);

        static::assertEquals('methodName', $helper->call('getMethod', [$label . '@methodName']));
    }

    /**
     * When method is not set in the stored container then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Method Not Found
     */
    public function testWhenMethodIsNotSetInTheStoredContainerThenThrowException()
    {
        $label = '\Rpc\Controller\MainController';

        \IoC\Container::getInstance()->register($label, function ($serialization) {
            if ($serialization == 'rpc') {
                return new FakeControllerUnderTestGetController();
            }
        });

        $object = new RpcDispatcher();
        $helper = new \ProtectedHelper($object);

        $helper->call('getMethod', [$label . '@login']);
    }
}

class FakeControllerUnderTestGetMethod
{
    public function methodName($args)
    {
        return '123';
    }
}