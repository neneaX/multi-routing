<?php
namespace tests\Router\Helpers\JsonParser;

use MultiRouting\Helpers\JsonParser;

class getCalledMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When the request is valid then return the method name.
     */
    public function testWhenTheRequestIsValidThenReturnTheMethodName()
    {
        $request = (object)[
            'method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        static::assertEquals('getCall', $object->getCalledMethod());
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

        $object->getCalledMethod();
    }

    /**
     * When the request is incomplete then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No method found
     */
    public function testWhenTheResultIsIncompleteThenThrowException()
    {
        $request = (object)[
            'no-method' => 'getCall'
        ];

        $object = new JsonParser();

        $helper = new \ProtectedHelper($object);
        $helper->setValue('request', $request);

        $object->getCalledMethod();
    }
}
