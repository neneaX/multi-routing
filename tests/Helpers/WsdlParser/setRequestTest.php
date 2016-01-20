<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class setRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    protected $requestString;

    public function setUp()
    {
//        POST /InStock HTTP/1.1
//        Host: www.bookshop.org
//        Content-Type: application/soap+xml; charset=utf-8
//        Content-Length: nnn

        $this->requestString = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <m:GetBookPrice>
                    <m:BookName>The Fleamarket</m:BookName>
                </m:GetBookPrice>
            </soap:Body>
        </soap:Envelope>';

        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the request is a string then store as a domdocument
     */
    public function testWhenTheRequestIsAStringThenStoreAsADomdocument()
    {
        $this->parser->setRequest($this->requestString);
        $helper = new \ProtectedHelper($this->parser);

        $request = $helper->getValue('request');
        static::assertInstanceOf('\DOMDocument', $request);

        $expected = '<?xml version="1.0"?>' . PHP_EOL .
            '<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope" soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding"><soap:Body xmlns:m="http://www.bookshop.org/prices">' .
            '<m:GetBookPrice><m:BookName>The Fleamarket</m:BookName></m:GetBookPrice></soap:Body>' .
            '</soap:Envelope>' . PHP_EOL;

        static::assertEquals($expected, $request->saveXML());
    }

    /**
     * When the request is a domdocument then store as a domdocument
     */
    public function testWhenTheRequestIsADomdocumentThenStoreAsADomdocument()
    {
        $request = new \DOMDocument('1.0', 'UTF-8');
        $request->preserveWhiteSpace = false;
        $request->loadXML($this->requestString);

        $this->parser->setRequest($request);
        $helper = new \ProtectedHelper($this->parser);

        $request = $helper->getValue('request');
        static::assertSame($request, $request);
    }

    /**
     * When the request is invalid then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage The input is not allowed.
     */
    public function testWhenTheRequestIsInvalidThenThrowException()
    {
        $request = new \stdClass;
        $this->parser->setRequest($request);
    }
}
