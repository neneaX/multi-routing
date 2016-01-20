<?php
namespace tests\Router\Exceptions\SerializationException;

use MultiRouting\Router\Exceptions\SerializationException;

class constructTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When message is empty then set default message.
     * @dataProvider dataProviderEmpty
     */
    public function testWhenMessageIsEmptyThenSetDefaultMessage()
    {
        $expected = SerializationException::MESSAGE_PREFIX . SerializationException::DEFAULT_MESSAGE;

        $object = new SerializationException();
        static::assertSame($expected, $object->getMessage());
    }

    public function dataProviderEmpty()
    {
        return [
            [null],
            [''],
            [[]],
            [0],
            [new \stdClass()]
        ];
    }

    /**
     * When message is not empty then prepend prefix and set as exception message.
     */
    public function testWhenMessageIsNotEmptyThenPrependPrefixAndSetAsExceptionMessage()
    {
        $message = 'Too many connections.';
        $expected = SerializationException::MESSAGE_PREFIX . $message;

        $object = new SerializationException($message);
        static::assertSame($expected, $object->getMessage());
    }

    /**
     * Code will be set as code.
     * @dataProvider dataProviderStatusCodes
     */
    public function testCodeWillBeSetAsCode($statusCode)
    {
        $object = new SerializationException('Generic error', $statusCode);
        static::assertSame($statusCode, $object->getCode());
    }

    /**
     * When code is not set then set default 400 status code.
     */
    public function testWhenCodeIsNotSetThenSetDefault400StatusCode()
    {
        $object = new SerializationException();
        static::assertSame(400, $object->getCode());
    }

    /**
     * When previous exception is given then store.
     */
    public function testWhenPreviousExceptionIsGivenThenStore()
    {
        $previous = new \InvalidArgumentException();

        $object = new SerializationException('Not found.', 404, $previous);
        static::assertSame($previous, $object->getPrevious());
    }

    public function dataProviderStatusCodes()
    {
        return [
            [200],
            [201],
            [301],
            [404],
            [501],
        ];
    }
}
