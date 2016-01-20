<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class getCalledParamsTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    protected $requestStringNamespaced;

    protected $requestStringNotNamespaced;

    public function setUp()
    {
        $this->requestStringNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <m:GetBookPrice>
                    <m:BookName>The Fleamarket 1</m:BookName>
                </m:GetBookPrice>
            </soap:Body>
        </soap:Envelope>';

        $this->requestStringNotNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <GetBookISBN>
                    <BookTitle>The Fleamarket 2</BookTitle>
                    <PublishedYear>2014</PublishedYear>
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
     * When the request is invalid then throw exception
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Request not set
     */
    public function testWhenTheRequestIsInvalidThenThrowException()
    {
        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('request', '');

        $this->parser->getCalledParams();
    }

    /**
     * When the request is valid with body and a namespaced method then return params
     */
    public function testWhenTheRequestIsValidWithBodyAndANamespacedMethodThenReturnParams()
    {
        $expected = [
            'BookName' => 'The Fleamarket 1',
        ];

        $this->parser->setRequest($this->requestStringNamespaced);
        static::assertEquals($expected, $this->parser->getCalledParams());
    }

    /**
     * When the request is valid with body and a non-namespaced method then return params
     */
    public function testWhenTheRequestIsValidWithBodyAndANonNamespacedMethodThenReturnParams()
    {
        $expected = [
            'BookTitle' => 'The Fleamarket 2',
            'PublishedYear' => '2014',
        ];

        $this->parser->setRequest($this->requestStringNotNamespaced);
        static::assertEquals($expected, $this->parser->getCalledParams());
    }
}
