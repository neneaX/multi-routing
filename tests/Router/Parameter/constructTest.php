<?php
namespace tests\Router\Parameter;

use MultiRouting\Router\Parameter;

class constructTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will store the input value.
     */
    public function testWhenCalledThenWillStoreTheInputValue()
    {
        $object = new Parameter('nameme', 'v123');
        static::assertEquals('nameme', (new \ProtectedHelper($object))->getValue('name'));
        static::assertEquals('v123', (new \ProtectedHelper($object))->getValue('value'));
    }
}
