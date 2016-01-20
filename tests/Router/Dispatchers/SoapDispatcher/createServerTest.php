<?php
namespace tests\Router\Dispatchers\SoapDispatcher;


use MultiRouting\Router\Dispatchers\SoapDispatcher;

class runRouteTest extends \PHPUnit_Framework_TestCase
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
        $object = new SoapDispatcher();
        $helper = new \ProtectedHelper($object);

        /** @var \SoapServer $response */
        $response = $helper->call('createServer', [WSDL_PATH, ['cache_wsdl' => 0]]);
        static::assertInstanceOf('\SoapServer', $response);
    }
}
