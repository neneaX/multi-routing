<?php
namespace tests\example\SOAP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When calling the WSDL file then deliver content.
     * @todo Check if the returned content is a valid XML (and provoke a notice and catch with output buffer & clean).
     */
    public function testWhenCallingTheWSDLFileThenDeliverContent()
    {
        $client = new Client();
        $options = [
            'headers' => [
                'Accept' => 'text/xml',
            ],
            'proxy' => ''
        ];
        $contents = null;

        try {
            $response = $client->get(APP_URL . '/wsdl', $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertContains('getItemRequest', $contents);
    }

    /**
     * When accessing the getItem route then return a structure.
     */
    public function testWhenAccessingTheGetItemRouteThenReturnAStructure()
    {
        $client = new \SoapClient(APP_URL . '/wsdl', [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'location' => APP_URL . '/soap'
        ]);

        $response = '';

        try {
            $response = $client->getItem('TNE');

        } catch (\SoapFault $e) {
            static::fail($e->getMessage());
        }

        static::assertNotNull($response);
        static::assertTrue(is_object($response));
        static::assertEquals('TNE', $response->Name);
    }

    /**
     * When calling a method expecting to throw a SoapFault then return the expected message.
     */
    public function testWhenCallingAMethodExpectingToThrowASoapFaultThenReturnTheExpectedMessage()
    {
        $client = new \SoapClient(APP_URL . '/wsdl', [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'location' => APP_URL
        ]);

        $response = '';

        try {
            $response = $client->getError('pineapple');
            static::fail('was expecting a SoapFault, got [' . serialize($response) . '] instead');

        } catch (\SoapFault $e) {
            static::assertEquals('Cound not find [pineapple].', $e->getMessage());
        }
    }

    /**
     * When calling a method that is not defined in the WSDL then throw SoapFault with expected message.
     */
    public function testWhenCallingAMethodThatIsNotDefinedInTheWSDLThenThrowSoapFaultWithExpectedMessage()
    {
        $client = new \SoapClient(APP_URL . '/wsdl', [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'location' => APP_URL
        ]);

        try {
            $response = $client->getNonDefinedMethod();
            static::fail('was expecting a SoapFault, got [' . serialize($response) . '] instead');

        } catch (\SoapFault $e) {
            static::assertEquals('Function ("getNonDefinedMethod") is not a valid method for this service', $e->getMessage());
        }
    }

    /**
     * When calling a method that exists in the WSDL but is not defined in the routes then throw SoapFault with expected message.
     */
    public function testWhenCallingAMethodThatExistsInTheWSDLButIsNotDefinedInTheRoutesThenThrowSoapFaultWithExpectedMessage()
    {
        $client = new \SoapClient(APP_URL . '/wsdl', [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'location' => APP_URL
        ]);

        try {
            $response = $client->ping();
            static::fail('was expecting a SoapFault, got [' . serialize($response) . '] instead');

        } catch (\SoapFault $e) {
            // @note will fail with "looks like we got no XML document"
            static::assertEquals('???', $e->getMessage());
        }
    }
}
