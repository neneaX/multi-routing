<?php
namespace tests\Router\Request\Handlers\RestHandler;


use MultiRouting\Router\Request\Handlers\RestHandler;

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

        $object = new RestHandler($controller);
        $helper = new \ProtectedHelper($object);

        static::assertSame($controller, $helper->getValue('controller'));
    }
}
