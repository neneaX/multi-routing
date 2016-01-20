<?php
namespace tests\Router\Request\Interpreters\SoapInterpreter;


use IoC\Container;
use MultiRouting\Router\Request\Interpreters\SoapInterpreter;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will store the wsdl from the dependency container.
     */
    public function testWillStoreTheWsdlFromTheDependencyContainer()
    {
        $instance = \Mockery::mock('\MultiRouting\Helpers\WsdlParser')->makePartial();
        Container::getInstance()->register('Helpers\WsdlParser', $instance);

        $object = new SoapInterpreter();
        $helper = new \ProtectedHelper($object);

        static::assertSame($instance, $helper->getValue('WsdlParser'));
    }
}
