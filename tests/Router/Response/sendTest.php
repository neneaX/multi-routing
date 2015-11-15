<?php
namespace tests\Router\Response;


class sendTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Method will send the headers and the content.
     */
    public function testMethodWillSendTheHeadersAndTheContentAndReturnNull()
    {
        $object = \Mockery::mock('\MultiRouting\Router\Response')->makePartial();
        $object->shouldReceive('sendHeaders')->once();
        $object->shouldReceive('sendContent')->once();

        static::assertSame($object, $object->send());
    }
}
