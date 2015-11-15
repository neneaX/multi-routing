<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class validateMethodExistsTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    protected $requestStringNamespaced;

    protected $requestStringNotNamespaced;

    protected $wsdlFile;

    public function setUp()
    {
        $this->wsdlFile = __DIR__ . '/../../soap-wsdl-literal.sample.wsdl';

        $this->requestStringNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <m:getGreeting>
                    <m:BookName>The Fleamarket 1</m:BookName>
                    <m:TicketPrice>200</m:TicketPrice>
                    <m:Currency>EUR</m:Currency>
                </m:getGreeting>
            </soap:Body>
        </soap:Envelope>';

        $this->requestStringNotNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <add>
                    <BookTitle>The Fleamarket 2</BookTitle>
                    <PublishedYear>2014</PublishedYear>
                </add>
            </soap:Body>
        </soap:Envelope>';

        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the request has namespaced method that is found in the loaded wsdl file then return null
     */
    public function testWhenTheRequestHasNamespacedMethodThatIsFoundInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNamespaced);

        static::assertNull($this->parser->validateMethodExists('getGreeting'));
    }

    /**
     * When the request has namespaced method that is not found in the loaded wsdl file then return null
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Method does not exists in WSDL
     */
    public function testWhenTheRequestHasNamespacedMethodThatIsNotFoundInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNamespaced);

        $this->parser->validateMethodExists('SleepAllHumans');
    }

    /**
     * When the request has namespaced method is checked and should be in the loaded wsdl file then return null
     */
    public function testWhenTheRequestHasNamespacedMethodIsCheckedAndShouldBeInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNamespaced);

        $this->parser->validateMethodExists();
    }

    /**
     * When the request has non-namespaced method that is found in the loaded wsdl file then return null
     */
    public function testWhenTheRequestHasNonNamespacedMethodThatIsFoundInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNotNamespaced);

        static::assertNull($this->parser->validateMethodExists('add'));
    }

    /**
     * When the request has non-namespaced method that is not found in the loaded wsdl file then return null
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Method does not exists in WSDL
     */
    public function testWhenTheRequestHasNonNamespacedMethodThatIsNotFoundInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNotNamespaced);

        $this->parser->validateMethodExists('setDate');
    }

    /**
     * When the request has non-namespaced method is checked and should be in the loaded wsdl file then return null
     */
    public function testWhenTheRequestHasNonNamespacedMethodIsCheckedAndShouldBeInTheLoadedWsdlFileThenReturnNull()
    {
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($this->wsdlFile)->andReturn(true);

        $this->parser->setWsdl($this->wsdlFile);
        $this->parser->setRequest($this->requestStringNotNamespaced);

        $this->parser->validateMethodExists();
    }
}
