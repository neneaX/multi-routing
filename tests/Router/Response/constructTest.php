<?php
namespace tests\Router\Response;


use MultiRouting\Router\Response;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will store the content and status.
     */
    public function testWillStoreTheContentAndStatus()
    {
        $headers = [
            'Location' => 'http://somewhere.com/path/'
        ];

        $status = mt_rand(100, 500);
        $content = uniqid();

        $object = new Response($content, $status, $headers);
        $helper = new \ProtectedHelper($object);

        static::assertEquals($content, $helper->getValue('content'));
        static::assertEquals($status, $helper->getValue('statusCode'));
    }


    /**
     * By default the content is empty.
     */
    public function testByDefaultTheContentIsEmpty()
    {
        $object = new Response();
        $helper = new \ProtectedHelper($object);

        static::assertEquals('', $helper->getValue('content'));
    }

    /**
     * By default the status is 200.
     */
    public function testByDefaultTheStatusIs200()
    {
        $object = new Response();
        $helper = new \ProtectedHelper($object);

        static::assertEquals(200, $helper->getValue('statusCode'));
    }
}
