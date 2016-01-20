<?php
namespace tests\Router\Helpers\JsonParser;

use MultiRouting\Helpers\JsonParser;

class setRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When the request is a string then store the json decoded string.
     */
    public function testWhenTheRequestIsAStringThenStoreTheJsonDecodedString()
    {
        $object = new JsonParser();
        $helper = new \ProtectedHelper($object);

        $requestRaw = (object)['cargo' => 'get'];
        $object->setRequest(json_encode($requestRaw));

        static::assertEquals($requestRaw, $helper->getValue('request'));
    }

    /**
     * When the request is a string that fails to json_decode then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage The input is not allowed.
     */
    public function testWhenTheRequestIsAStringThatFailsToJsonDecodeThenThrowException()
    {
        $object = new JsonParser();
        $object->setRequest('non-json-decodable-data');
    }

    /**
     * When the request is an object then store it.
     */
    public function testWhenTheRequestIsAnObjectThenStoreIt()
    {
        $object = new JsonParser();
        $helper = new \ProtectedHelper($object);

        $requestRaw = (object)['cargo' => 'get'];
        $object->setRequest($requestRaw);

        static::assertSame($requestRaw, $helper->getValue('request'));
    }


    /**
     * When the request is not a json_decodable string or an object then throw an exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage The input is not allowed.
     */
    public function testWhenTheRequestIsNotAJsonDecodableStringOrAnObjectThenThrowAnException()
    {
        $object = new JsonParser();
        $object->setRequest([]);
    }
}
