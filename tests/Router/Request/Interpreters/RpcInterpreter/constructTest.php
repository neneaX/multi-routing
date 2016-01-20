<?php
namespace tests\Router\Request\Interpreters\RpcInterpreter;


use IoC\Container;
use MultiRouting\Router\Request\Interpreters\RpcInterpreter;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will store the jsonparser from the dependency container.
     */
    public function testWillStoreTheJsonparserFromTheDependencyContainer()
    {
        $instance = \Mockery::mock('\MultiRouting\Helpers\JsonParser')->makePartial();
        Container::getInstance()->register('Helpers\JsonParser', $instance);

        $object = new RpcInterpreter();
        $helper = new \ProtectedHelper($object);

        static::assertSame($instance, $helper->getValue('JsonParser'));
    }
}
