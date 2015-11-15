<?php
namespace tests\Router\Request\Handlers\SoapHandler;


use MultiRouting\Router\Request\Handlers\SoapHandler;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * The given controller is stored.
     */
    public function testTheGivenControllerIsStored()
    {
        $controller = new \stdClass();

        $object = new SoapHandler($controller);
        $helper = new \ProtectedHelper($object);

        static::assertSame($controller, $helper->getValue('controller'));
    }
}
