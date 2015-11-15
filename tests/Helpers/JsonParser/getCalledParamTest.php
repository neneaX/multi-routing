<?php
namespace tests\Router\Helpers\JsonParser;

use MultiRouting\Helpers\JsonParser;

class getCalledParamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When the request is invalid then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Request not set
     */
    public function testWhenTheRequestIsInvalidThenThrowException()
    {
        $request = [
            'method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        $object->getCalledParam('userId');
    }

    /**
     * When the request is valid but no params are set then return null.
     */
    public function testWhenTheRequestIsValidButNoParamsAreSetThenReturnNull()
    {
        $request = (object)[
            'method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertNull($object->getCalledParam('userId'));
    }

    /**
     * When the params are set as array then return them as an assoc array.
     */
    public function testWhenTheParamsAreSetAsArrayThenReturnThemAsAnAssocArray()
    {
        $expected = [
            'a' => 'b',
            'userId' => 'd11',
        ];

        $request = (object)[
            'method' => 'getCall',
            'params' => $expected
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals('d11', $object->getCalledParam('userId'));
    }

    /**
     * When the params are set as object then return them as an assoc array.
     */
    public function testWhenTheParamsAreSetThenReturnThemAsAnAssocArray()
    {
        $request = (object)[
            'method' => 'getCall',
            'params' => (object)[
                'userId' => 444,
                'c' => 'd',
            ]
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals(444, $object->getCalledParam('userId'));
    }
}
