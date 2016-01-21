<?php
namespace tests\example\Base;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When accessing the GET / path then return expected string response.
     */
    public function testWhenAccessingTheGETPathThenReturnExpectedStringResponse()
    {
        $client = new Client();
        $options = ['proxy' => ''];
        $contents = null;

        try {
            $response = $client->get(APP_URL, $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertEquals('Welcome!', $contents);
    }

    /**
     * When accessing the GET /foobar path the return a json response with the input params
     */
    public function testWhenAccessingTheGETFoobarPathTheReturnAJsonResponseWithTheInputParams()
    {
        $client = new Client();
        $options = ['proxy' => ''];
        $contents = null;

        try {
            $response = $client->get(APP_URL . '/foobar/param1_value/param2_value', $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertEquals('{"foo":"param1_value","bar":"param2_value"}', $contents);
    }

    /**
     * When accessing the POST / path the return the expected response.
     *
     * Had the following error:
        1) tests\example\Base\RoutesTest::testWhenAccessingThePOSTPathTheReturnTheExpectedResponse
        Failed asserting that two strings are equal.
        --- Expected
        +++ Actual
        @@ @@
        -'{"foo":"sand","bar":"witch"}'
        +'<br />
        +<b>Deprecated</b>:  Automatically populating $HTTP_RAW_POST_DATA is deprecated and will be removed in a future version. To avoid this warning set 'always_populate_raw_post_data' to '-1' in php.ini and use the php://input stream instead. in <b>Unknown</b> on line <b>0</b><br />
        +<br />
        +<b>Warning</b>:  Cannot modify header information - headers already sent in <b>Unknown</b> on line <b>0</b><br />
        +{"foo":"sand","bar":"witch"}'
     */
    public function testWhenAccessingThePOSTPathTheReturnTheExpectedResponse()
    {
        $client = new Client();
        $options = [
            'proxy' => '',
            'body' => '[]'
        ];

        $contents = null;

        try {
            $response = $client->post(APP_URL . '/sandwich', $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertEquals('{"foo":"sand","bar":"witch"}', $contents);
    }

    /**
     * When accessing a non-defined route then throw 404 exception.
     */
    public function testWhenAccessingANonDefinedRouteThenThrow404Exception()
    {
        $client = new Client();
        $options = [
            'proxy' => '',
        ];

        $contents = null;

        try {
            $response = $client->get(APP_URL . '/something/something', $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::assertEquals(404, $e->getResponse()->getStatusCode());
            $contents = $e->getResponse()->getBody()->getContents();
        };

        static::assertEquals('Page not found', $contents);
    }
}
