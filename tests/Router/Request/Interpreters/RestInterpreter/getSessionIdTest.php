<?php
namespace tests\Router\Request\Handlers\SoapHandler;


use MultiRouting\Router\Request\Interpreters\RestInterpreter;

class getSessionIdTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will return null.
     */
    public function testWillReturnNull()
    {
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $object = new RestInterpreter();

        static::assertNull($object->getSessionId($request));
    }
}
