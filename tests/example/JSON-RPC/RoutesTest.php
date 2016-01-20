<?php
namespace tests\example\JSON_RPC;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoutesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When accessing the getDetails route then use the input and return expected JSON-RPC response format.
     */
    public function testWhenAccessingTheGetDetailsRouteThenUseTheInputAndReturnExpectedJSONRPCResponseFormat()
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 2,
            'method' => 'getDetails',
            'params' => ['one'],
        ];

        $client = new Client();
        $options = [
            'proxy' => '',
            'body' => json_encode($request)
        ];
        $contents = null;

        try {
            $response = $client->post(APP_URL, $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertEquals('{"jsonrpc":"2.0","id":2,"result":{"content":"one","length":3,"type":"string"}}', $contents);
    }

    /**
     * When accessing the second JSON-RPC route then return expected manually triggered error message in JSON-RPC response format.
     */
    public function testWhenAccessingTheSecondJSONRPCRouteThenReturnExpectedManuallyTriggeredErrorMessageInJSONRPCResponseFormat()
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 3,
            'method' => 'getError',
            'params' => ['two-one-nil'],
        ];

        $client = new Client();
        $options = [
            'proxy' => '',
            'body' => json_encode($request)
        ];
        $contents = null;

        try {
            $response = $client->post(APP_URL, $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            static::fail($e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertEquals('{"jsonrpc":"2.0","id":3,"error":{"code":-10011,"message":"Something went wrong [two-one-nil].","data":null}}', $contents);
    }

    /**
     * When calling a method that is not defined then throw 404 exception.
     */
    public function testWhenCallingAMethodThatIsNotDefinedThenThrow404Exception()
    {
        $request = [
            'jsonrpc' => '2.0',
            'id' => 3,
            'method' => 'getSomeUndefinedMethod',
            'params' => [],
        ];

        $client = new Client();
        $options = [
            'proxy' => '',
            'body' => json_encode($request)
        ];
        $contents = null;

        try {
            $response = $client->post(APP_URL, $options);
            $contents = $response->getBody()->getContents();

        } catch (ClientException $e) {
            // @note will fail
            // @fixme treat this case
            static::assertEquals('???', $e->getMessage() . ': ' . $e->getResponse()->getBody()->getContents());
        }

        static::assertNull($contents);
    }
}
