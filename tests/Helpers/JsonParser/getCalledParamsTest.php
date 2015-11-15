<?php
namespace tests\Router\Helpers\JsonParser;

use MultiRouting\Helpers\JsonParser;

class getCalledParamsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When the request is valid but no params are set then return empty array.
     */
    public function testWhenTheRequestIsValidButNoParamsAreSetThenReturnEmptyArray()
    {
        $request = (object)[
            'method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals([], $object->getCalledParams());
    }

    /**
     * When the result is invalid then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Request not set
     */
    public function testWhenTheResultIsInvalidThenThrowException()
    {
        $request = [
            'method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        $object->getCalledParams();
    }

    /**
     * When the params are set as array then return them as an assoc array.
     */
    public function testWhenTheParamsAreSetAsArrayThenReturnThemAsAnAssocArray()
    {
        $expected = [
            'a' => 'b',
            'c' => 'd',
        ];

        $request = (object)[
            'method' => 'getCall',
            'params' => $expected
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals($expected, $object->getCalledParams());
    }

    /**
     * When the params are set as object then return them as an assoc array.
     */
    public function testWhenTheParamsAreSetThenReturnThemAsAnAssocArray()
    {
        $expected = [
            'a' => 'b',
            'c' => 'd',
        ];

        $request = (object)[
            'method' => 'getCall',
            'params' => (object)[
                'a' => 'b',
                'c' => 'd',
            ]
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals($expected, $object->getCalledParams());
    }
}
