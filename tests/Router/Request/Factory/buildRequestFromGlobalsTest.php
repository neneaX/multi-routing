<?php
namespace tests\Router\Request\Factory;


class buildRequestFromGlobalsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Checks if the route and the request have the same serialization and return true if so.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameSerializationAndReturnTrueIfSo()
    {
        $expected = \Mockery::mock('\MultiRouting\Router\Request\Request')->makePartial();

        $mock = \Mockery::mock('\MultiRouting\Router\Request\Factory')->makePartial();
        $mock->shouldReceive('buildRequest')->once()->with($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, file_get_contents('php://input'))->andReturn($expected);

        static::assertSame($expected, $mock->buildRequestFromGlobals());
    }
}
