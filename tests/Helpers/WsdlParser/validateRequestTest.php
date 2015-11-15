<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class validateRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    protected $requestStringNamespaced;

    protected $requestStringNotNamespaced;

    public function setUp()
    {
        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the request is a domdocument then return null
     */
    public function testWhenTheRequestIsADomdocumentThenReturnNull()
    {
        $object = new WsdlParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', new \DOMDocument());

        static::assertNull($helper->call('validateRequest'));
    }

    /**
     * When the request is not a domdocument then return null
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Request not set
     */
    public function testWhenTheRequestIsNotADomdocumentThenReturnNull()
    {
        $object = new WsdlParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', new \stdClass);

        $helper->call('validateRequest');
    }
}
