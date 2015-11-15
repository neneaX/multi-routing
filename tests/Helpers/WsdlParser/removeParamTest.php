<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class removeParamTest extends \PHPUnit_Framework_TestCase
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
                    <m:TicketPrice>200</m:TicketPrice>
                    <m:Currency>EUR</m:Currency>
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

        $this->parser->getCalledParam('BookTitle');
    }

    /**
     * When the request is valid with body and a namespaced method then return true after removed called param if found
     */
    public function testWhenTheRequestIsValidWithBodyAndANamespacedMethodThenReturnTrueAfterRemovedCalledParamIfFound()
    {
        $this->parser->setRequest($this->requestStringNamespaced);
        static::assertTrue($this->parser->removeParam('BookName'));

        $expected = [
            'TicketPrice' => 200,
            'Currency' => 'EUR'
        ];

        static::assertEquals($expected, $this->parser->getCalledParams());
    }

    /**
     * When the request is valid with body and a namespaced method then return false after trying to remove a parameter that is not found
     */
    public function testWhenTheRequestIsValidWithBodyAndANamespacedMethodThenReturnFalseAfterTryingToRemoveAParameterThatIsNotFound()
    {
        $this->parser->setRequest($this->requestStringNamespaced);
        static::assertFalse($this->parser->removeParam('AuthorName'));
    }

    /**
     * When the request is valid with body and a non-namespaced method then return true after removed called param if found
     */
    public function testWhenTheRequestIsValidWithBodyAndANonNamespacedMethodThenReturnTrueAfterRemovedCalledParamIfFound()
    {
        // when first parameter
        $this->parser->setRequest($this->requestStringNotNamespaced);
        static::assertTrue($this->parser->removeParam('BookTitle'));
        static::assertEquals(['PublishedYear' => '2014'], $this->parser->getCalledParams());

        // when not first parameter
        $this->parser->setRequest($this->requestStringNotNamespaced);
        static::assertTrue($this->parser->removeParam('PublishedYear'));
        static::assertEquals(['BookTitle' => 'The Fleamarket 2'], $this->parser->getCalledParams());
    }

    /**
     * When the request is valid with body and a non-namespaced method then return false after trying to remove a parameter that is not found
     */
    public function testWhenTheRequestIsValidWithBodyAndANonNamespacedMethodThenReturnFalseAfterTryingToRemoveAParameterThatIsNotFound()
    {
        $this->parser->setRequest($this->requestStringNotNamespaced);
        static::assertFalse($this->parser->removeParam('AuthorName'));
    }
}
