<?php
namespace tests\Router\Router;


use MultiRouting\Router\Router;

/**
 * @note DEFAULT_SERIALIZATION = rpc here, while for Url is rest
 */
class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will store a new routecollection
     */
    public function testWillStoreANewRoutecollection()
    {
        $router = new Router();
        $helper = new \ProtectedHelper($router);

        static::assertInstanceOf('\MultiRouting\Router\RouteCollection', $helper->getValue('routes'));
    }
}
