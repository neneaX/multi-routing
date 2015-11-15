<?php
namespace tests\Router\Exceptions\UrlException;

use MultiRouting\Router\Exceptions\UrlException;

class constructTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When message is empty then set default message.
     * @dataProvider dataProviderEmpty
     */
    public function testWhenMessageIsEmptyThenSetDefaultMessage()
    {
        $expected = UrlException::MESSAGE_PREFIX . UrlException::DEFAULT_MESSAGE;

        $object = new UrlException();
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
        $expected = UrlException::MESSAGE_PREFIX . $message;

        $object = new UrlException($message);
        static::assertSame($expected, $object->getMessage());
    }

    /**
     * Code will be set as code.
     * @dataProvider dataProviderStatusCodes
     */
    public function testCodeWillBeSetAsCode($statusCode)
    {
        $object = new UrlException('Generic error', $statusCode);
        static::assertSame($statusCode, $object->getCode());
    }

    /**
     * When code is not set then set default 400 status code.
     */
    public function testWhenCodeIsNotSetThenSetDefault400StatusCode()
    {
        $object = new UrlException();
        static::assertSame(400, $object->getCode());
    }

    /**
     * When previous exception is given then store.
     */
    public function testWhenPreviousExceptionIsGivenThenStore()
    {
        $previous = new \InvalidArgumentException();

        $object = new UrlException('Not found.', 404, $previous);
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
