<?php
namespace tests\Router\Response;


use MultiRouting\Router\Response;

class sendHeadersTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Method has fluent interface.
     */
    public function testMethodHasFluentInterface()
    {
        $object = new Response();
        static::assertSame($object, $object->sendHeaders());
    }
}
