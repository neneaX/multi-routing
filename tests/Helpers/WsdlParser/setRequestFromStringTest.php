<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class setRequestFromStringTest extends \PHPUnit_Framework_TestCase
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
     * When request is namespaced then store.
     */
    public function testWhenRequestIsNamespacedThenStore()
    {
        $request = new \DOMDocument('1.0', 'UTF-8');
        $request->loadXML($this->requestStringNamespaced);

        $helper = new \ProtectedHelper($this->parser);
        $response = $helper->call('setRequestFromString', [$this->requestStringNamespaced]);

        $expected = '<?xml version="1.0"?>' . PHP_EOL . '<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope" soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding"><soap:Body xmlns:m="http://www.bookshop.org/prices"><m:GetBookPrice><m:BookName>The Fleamarket</m:BookName></m:GetBookPrice></soap:Body></soap:Envelope>' . PHP_EOL;

        static::assertNull($response);
        static::assertInstanceOf('\DOMDocument', $helper->getValue('request'));
        static::assertEquals($expected, $helper->getValue('request')->saveXML());
    }

    /**
     * When request is not namespaced then store.
     */
    public function testWhenRequestIsNotNamespacedThenStore()
    {
        $request = new \DOMDocument('1.0', 'UTF-8');
        $request->loadXML($this->requestStringNotNamespaced);

        $helper = new \ProtectedHelper($this->parser);
        $response = $helper->call('setRequestFromString', [$this->requestStringNotNamespaced]);

        $expected = '<?xml version="1.0"?>' . PHP_EOL . '<soap:Envelope xmlns:soap="http://www.w3.org/2001/12/soap-envelope" soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding"><soap:Body xmlns:m="http://www.bookshop.org/prices"><GetBookISBN><BookName>The Fleamarket</BookName></GetBookISBN></soap:Body></soap:Envelope>' . PHP_EOL;

        static::assertNull($response);
        static::assertInstanceOf('\DOMDocument', $helper->getValue('request'));
        static::assertEquals($expected, $helper->getValue('request')->saveXML());
    }

    /**
     * When request is invalid then store as well.
     */
    public function testWhenRequestIsInvalidThenStoreAsWell()
    {
        $string = '<home>ô</home>';

        $request = new \DOMDocument('1.0', 'UTF-8');
        $request->loadXML($string);

        $helper = new \ProtectedHelper($this->parser);
        $response = $helper->call('setRequestFromString', [$string]);

        $expected = '<?xml version="1.0"?>' . PHP_EOL . '<home>&#xF4;</home>' . PHP_EOL;

        static::assertNull($response);
        static::assertInstanceOf('\DOMDocument', $helper->getValue('request'));
        static::assertEquals($expected, $helper->getValue('request')->saveXML());
    }
}
