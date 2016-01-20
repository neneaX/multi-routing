<?php
namespace tests\Router\Request\Handlers\SoapHandler;


class getParametersTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will return a parameter collection.
     */
    public function testWillReturnAParameterCollection()
    {
        $intentValue = '/order/{reference}/history/{logId}/';
        $resourceUrl = '/order/1234/history/3/';

        $pattern = [
            'reference' => '[\d]+',
            'logId' => '[\d]+',
        ];

        $interpreter = \Mockery::mock('\MultiRouting\Router\Request\Interpreters\RestInterpreter')->makePartial();

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getIntent')->once()->andReturn($intentValue);
        $route->shouldReceive('getMatching')->once()->andReturn($pattern);

        $urlMock = \Mockery::mock('\MultiRouting\Router\Url')->makePartial();
        $urlMock->shouldReceive('getResourceUrl')->once()->andReturn($resourceUrl);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $response = $interpreter->getParameters($route, $request);

        static::assertInstanceOf('\MultiRouting\Router\ParameterCollection', $response);

        $expected = [
            'reference' => '1234',
            'logId' => '3',
        ];

        static::assertEquals($expected, $response->toArray());
    }
}
