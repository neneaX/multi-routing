<?php
namespace tests\Router\Request\Handlers\SoapHandler;


use MultiRouting\Router\Intent;
use MultiRouting\Router\Request\Interpreters\RestInterpreter;

class getIntentTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will return intent with stored resource url.
     */
    public function testWillReturnIntentWithStoredResourceUrl()
    {
        $resourceUrl = '/order/123/';

        $urlMock = \Mockery::mock('\MultiRouting\Router\Url')->makePartial();
        $urlMock->shouldReceive('getResourceUrl')->once()->andReturn($resourceUrl);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $expected = new Intent($resourceUrl);

        $object = new RestInterpreter();
        $response = $object->getIntent($request);

        static::assertEquals($expected, $response);
    }
}
