<?php
namespace tests\Router\Matching\HostValidator;


use MultiRouting\Router\Matching\HostValidator;

class matchesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will always return true.
     */
    public function testWillAlwaysReturnTrue()
    {
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        $validator = new HostValidator();
        static::assertTrue($validator->matches($route, $request));
    }
}
