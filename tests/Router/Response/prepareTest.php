<?php
namespace tests\Router\Response;


use MultiRouting\Router\Response;

class prepareTest extends \PHPUnit_Framework_TestCase
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
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        static::assertSame($object, $object->prepare($request));
    }
}
