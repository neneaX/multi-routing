<?php
namespace tests\Router\Request\Factory;


use MultiRouting\Router\Url;

class buildRequestTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Checks if the route and the request have the same serialization and return true if so.
     */
    public function testChecksIfTheRouteAndTheRequestHaveTheSameSerializationAndReturnTrueIfSo()
    {
        $query = ['limit' => 100];
        $body = ['id' => 1001, 'name' => 'version-1'];
        $cookies = ['c' => 'd'];
        $files = ['e' => 'f'];
        $server = [
            'SCRIPT_URL' => '/soap/1.1/path/to/action/',
            'REQUEST_METHOD' => 'POST'
        ];
        $content = 'from-input';

        $mock = \Mockery::mock('\MultiRouting\Router\Request\Factory')->makePartial();
        $response = $mock->buildRequest($query, $body, $cookies, $files, $server, $content);

        $helper = new \ProtectedHelper($response);

        static::assertEquals($query, $helper->getValue('query'));
        static::assertEquals($body, $helper->getValue('body'));
        static::assertEquals($cookies, $helper->getValue('cookies'));
        static::assertEquals($files, $helper->getValue('files'));
        static::assertEquals($server, $helper->getValue('server'));
        static::assertEquals($content, $helper->getValue('content'));

        /** @var Url $url */
        $url = $helper->getValue('url');
        static::assertInstanceOf('\MultiRouting\Router\Url', $url);

        static::assertEquals('/path/to/action/', $url->getResourceUrl());
        static::assertEquals('soap', $url->getSerialization());
        static::assertEquals('1.1', $url->getVersion());

        static::assertSame('post', $response->getMethod());
        static::assertSame($url, $response->getUrl());
        static::assertSame($query, $response->getQuery());
        static::assertSame($body, $response->getBody());
        static::assertSame($cookies, $response->getCookies());
        static::assertSame($files, $response->getFiles());
        static::assertSame($server, $response->getServer());
        static::assertSame($content, $response->getContent());
    }
}
