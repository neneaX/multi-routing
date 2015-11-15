<?php
namespace tests\Router\Request\Handlers\RpcHandler;


use MultiRouting\Router\Request\Handlers\RpcHandler;

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

        $object = new RpcHandler($controller);
        $helper = new \ProtectedHelper($object);

        static::assertSame($controller, $helper->getValue('controller'));
    }
}
