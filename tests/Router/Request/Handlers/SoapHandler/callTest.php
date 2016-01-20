<?php
namespace tests\Router\Request\Handlers\SoapHandler;


use MultiRouting\Helpers\WsdlParser;
use MultiRouting\Router\Request\Handlers\SoapHandler;

class callTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * If the given controller has the method then return its response.
     */
    public function testIfTheGivenControllerHasTheMethodThenReturnItsResponse()
    {
        $requestStringNamespaced = '<?xml version="1.0"?>
        <soap:Envelope
            xmlns:soap="http://www.w3.org/2001/12/soap-envelope"
            soap:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
            <soap:Body xmlns:m="http://www.bookshop.org/prices">
                <m:GetBookPrice>
                    <m:Hash>9999-0000-1111</m:Hash>
                    <m:BookName>Rise</m:BookName>
                    <m:Currency>EUR</m:Currency>
                </m:GetBookPrice>
            </soap:Body>
        </soap:Envelope>';

        $parser = new WsdlParser();
        $parser->setRequest($requestStringNamespaced);
        $params = $parser->getCalledParams();

        $controller = new ControllerUnderTestCall();
        $object = new SoapHandler($controller);

        $expected = [
            'Rise',
            'EUR',
        ];

        static::assertEquals($expected, $object->GetBookPrice($params));
    }

    /**
     * If the given controller has the method that throws exceptions then throw the exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Something happened.
     */
    public function testIfTheGivenControllerHasTheMethodThatThrowsExceptionsThenThrowTheException()
    {
        $params = ['hash-id-value', 'b', 'd'];

        $controller = new ControllerUnderTestCall();
        $object = new SoapHandler($controller);

        $object->getSomethingElse($params);
    }
}

class ControllerUnderTestCall
{
    public function GetBookPrice()
    {
        return func_get_args();
    }

    public function getSomethingElse($parameters)
    {
        throw new \Exception('Something happened. ' . json_encode($parameters));
    }
}
