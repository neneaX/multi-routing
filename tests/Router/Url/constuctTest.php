<?php
namespace tests\Router\Url;

use MultiRouting\Router\Exceptions\ResourceUrlException;
use MultiRouting\Router\Exceptions\SerializationException;
use MultiRouting\Router\Url;


class UrlWrapperConstructor extends Url
{
    public static function getAvailableSerializations()
    {
        return self::$availableSerializations;
    }
}


class constructTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \Registry::set('url.construct.sample.serialization.exception', new SerializationException('Inner exception'));
        \Registry::set('url.construct.sample.resourceurl.exception', new ResourceUrlException('Inner exception'));
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the given url is invalid will throw exception.
     *
     * @expectedException \MultiRouting\Router\Exceptions\UrlException
     */
    public function testWhenTheGivenUrlIsInvalidWillThrowException()
    {
        new Url('http://www.somewhere.com/an/invalid-url.html');
    }

    /**
     * Will store the given url if has the serialization part in the available list.
     * @note Strict URL format /{serialization}/{version}{resourceUrl}, where resourceUrl starts and ends with "/"
     *
     * @dataProvider dataProviderAvailableSerializations
     */
    public function testWillStoreTheGivenUrlIfHasTheSerializationPartInTheAvailableList($serialization)
    {
        $urlRawPartName = '/' . $serialization . '/1.1/cart/';

        $o = new Url($urlRawPartName);
        $helper = new \ProtectedHelper($o);

        static::assertEquals($urlRawPartName, $helper->getValue('raw'));
        return $o;
    }

    /**
     * Will set resourceUrl starting and ending with "/"
     *
     * @dataProvider dataProviderAvailableSerializations
     */
    public function testWillSetResourceUrlStartingAndEndingWithSlash($serialization)
    {
        $urlRawPartName = '/' . $serialization . '/1.1/cart/';

        $o = new Url($urlRawPartName);
        $helper = new \ProtectedHelper($o);

        static::assertEquals('/cart/', $helper->getValue('resourceUrl'));
    }

    public function dataProviderAvailableSerializations()
    {
        $wrapInArray = function ($value) {
            return [$value];
        };

        return array_map($wrapInArray, UrlWrapperConstructor::getAvailableSerializations());
    }

    /**
     * Will default on rest serialization.
     *
     * @note The default serialization is considered to be "rest",
     * @note The version is locked, with the value of the constant CURRENT_API_VERSION
     */
    public function testWillDefaultOnRestSerialization()
    {
        $urlString = '//1.1/target/';

        $o = new Url($urlString);
        $helper = new \ProtectedHelper($o);

        static::assertEquals($urlString, $helper->getValue('raw'));
        static::assertEquals('/target/', $helper->getValue('resourceUrl'));
        static::assertEquals('rest', $helper->getValue('serialization'));
    }

    /**
     * When serialization is invalid then throw exception.
     */
    public function testWhenSerializationIsInvalidThenThrowException()
    {
        try {
            new Url('/jsonrpc/1.1/target/');
            static::fail();

        } catch (\Exception $e) {
            static::assertEquals('URL Exception: The requested URL is invalid.', $e->getMessage());

            static::assertNotNull($e->getPrevious());
            static::assertEquals('URL Exception: The requested URL could not be parsed.', $e->getPrevious()->getMessage());

            static::assertNotNull($e->getPrevious()->getPrevious());
            static::assertEquals('Serialization Exception: The requested serialization is invalid.', $e->getPrevious()->getPrevious()->getMessage());

            static::assertNotNull($e->getPrevious()->getPrevious()->getPrevious());
            static::assertEquals('Serialization Exception: The requested serialization is not available.', $e->getPrevious()->getPrevious()->getPrevious()->getMessage());
        }
    }

    /**
     * When resourceUrl is invalid then throw exception.
     */
    public function testWhenResourceUrlIsInvalidThenThrowException()
    {
        $exception = \Registry::get('url.construct.sample.resourceurl.exception');

        try {
            new UrlWrapperResourceUrlWithException('/rest/1.1/test/');
            static::fail('Failed. Expecting resourceUrl exception.');

        } catch (\Exception $e) {

            static::assertEquals('URL Exception: The requested URL is invalid.', $e->getMessage());

            static::assertNotNull($e->getPrevious());
            static::assertEquals('URL Exception: The requested URL could not be parsed.', $e->getPrevious()->getMessage());

            static::assertNotNull($e->getPrevious()->getPrevious());
            static::assertSame($exception, $e->getPrevious()->getPrevious());
        }
    }
}

class UrlWrapperResourceUrlWithException extends Url
{
    protected function filterVersion($version)
    {
        // ignore
    }

    public function setResourceUrl($url)
    {
        if ($url === '/test/') {
            throw \Registry::get('url.construct.sample.resourceurl.exception');
        }
    }
    protected function filterSerialization($serialization)
    {
        // ignore
    }
}

class UrlWrapperSerializationWithException extends Url
{
    public function __construct($rawUrl)
    {
        // parent::__construct($rawUrl);
    }

    protected function filterVersion($version)
    {
        // ignore
    }

    protected function filterResourceUrl($version)
    {
        // ignore
    }

    protected function filterSerialization($serialization)
    {
        throw \Registry::get('url.construct.sample.serialization.exception');
    }

    public function setSerialization($serialization)
    {
        return parent::setSerialization($serialization);
    }
}
