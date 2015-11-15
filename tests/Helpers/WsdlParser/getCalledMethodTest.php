<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class getCalledMethodTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    protected $requestStringNamespaced;

    protected $requestStringNotNamespaced;

    public function setUp()
    {
//        POST /InStock HTTP/1.1
//        Host: www.bookshop.org
//        Content-Type: application/soap+xml; charset=utf-8
//        Content-Length: nnn

        $this->requestStringNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <m:GetBookPrice>
                    <m:BookName>The Fleamarket</m:BookName>
                </m:GetBookPrice>
            </soap:Body>
        </soap:Envelope>';

        $this->requestStringNotNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <GetBookISBN>
                    <BookName>The Fleamarket</BookName>
                </GetBookISBN>
            </soap:Body>
        </soap:Envelope>';

        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When request is namespaced then return the string.
     */
    public function testWhenRequestIsNamespacedThenReturnTheString()
    {
        $this->parser->setRequest($this->requestStringNamespaced);

        $response = $this->parser->getCalledMethod();
        static::assertEquals('GetBookPrice', $response);
    }

    /**
     * When request is not namespaced then return the string.
     */
    public function testWhenRequestIsNotNamespacedThenReturnTheString()
    {
        $this->parser->setRequest($this->requestStringNotNamespaced);

        $response = $this->parser->getCalledMethod();
        static::assertEquals('GetBookISBN', $response);
    }

    /**
     * When the request is invalid then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No method found
     */
    public function testWhenTheRequestIsInvalidThenThrowException()
    {
        $requestString = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
        </soap:Envelope>';

        $this->parser->setRequest($requestString);
        $this->parser->getCalledMethod();
    }

    /**
     * When the request is valid but no body elements are found then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No method found
     */
    public function testWhenTheRequestIsValidButNoBodyElementsAreFoundThenThrowException()
    {
        $requestString = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
        </soap:Envelope>';

        $DOM = new \DOMDocument();
        $DOM->loadXML($requestString);

        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('request', $DOM);

        $this->parser->getCalledMethod();
    }

    /**
     * When the request is valid with body but without a method then throw exception
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No method found
     */
    public function testWhenTheRequestIsValidWithBodyButWithoutAMethodThenThrowException()
    {
        $requestString = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">ame>
            </soap:Body>
        </soap:Envelope>';

        $DOM = new \DOMDocument();
        $DOM->loadXML($requestString);

        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('request', $DOM);

        $this->parser->getCalledMethod();
    }
    /**
     * When the request is valid with body and a namespaced method then return method name
     */
    public function testWhenTheRequestIsValidWithBodyAndANamespacedMethodThenReturnMethodName()
    {
        $this->parser->setRequest($this->requestStringNamespaced);
        static::assertEquals('GetBookPrice', $this->parser->getCalledMethod());
    }

    /**
     * When the request is valid with body and a non-namespaced method then return method name
     */
    public function testWhenTheRequestIsValidWithBodyAndANonNamespacedMethodThenReturnMethodName()
    {
        $this->parser->setRequest($this->requestStringNotNamespaced);
        static::assertEquals('GetBookISBN', $this->parser->getCalledMethod());
    }
}
