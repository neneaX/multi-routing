<?php
namespace tests\Router\Response;


use MultiRouting\Router\Response;

class sendContentTest extends \PHPUnit_Framework_TestCase
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
        $helper = new \ProtectedHelper($object);
        $helper->setValue('content', 'content-stored');

        ob_start();
        $response = $object->sendContent();
        ob_get_clean();

        static::assertSame($object, $response);
    }

    /**
     * Method will echo the content.
     */
    public function testMethodWillEchoTheContent()
    {
        $object = new Response();
        $helper = new \ProtectedHelper($object);
        $helper->setValue('content', 'content-stored');

        ob_start();
        $object->sendContent();
        $echo = ob_get_clean();

        static::assertEquals('content-stored', $echo);
    }
}
