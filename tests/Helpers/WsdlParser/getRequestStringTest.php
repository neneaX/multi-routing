<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class getRequestStringTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    public function setUp()
    {
        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When request is not an instance of domdocument then return empty string.
     */
    public function testWhenRequestIsNotAnInstanceOfDomdocumentThenReturnEmptyString()
    {
        static::assertEquals('', $this->parser->getRequestString());
    }

    /**
     * When request is an instance of domdocument then return saveXML's results.
     */
    public function testWhenRequestIsAnInstanceOfDomdocumentThenReturnSaveXMLSResults()
    {
        $expected = uniqid();

        $request = \Mockery::mock('\DOMDocument')->makePartial();
        $request->shouldReceive('saveXML')
            ->once()
            ->andReturn($expected);

        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('request', $request);

        static::assertEquals($expected, $this->parser->getRequestString());
    }
}
