<?php
namespace tests\Router\Url;

use MultiRouting\Router\Url;


class getRawTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will store the given url if has the serialization part in the available list.
     */
    public function testWillStoreTheGivenUrlIfHasTheSerializationPartInTheAvailableList()
    {
        $urlRawPartName = '/' . Url::DEFAULT_SERIALIZATION . '/1.1/cart/';
        $object = new Url($urlRawPartName);

        static::assertEquals($urlRawPartName, $object->getRaw());
    }
}
