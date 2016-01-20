<?php
namespace tests\Router\Intent;

use MultiRouting\Router\Intent;

class constructTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will store the input value.
     */
    public function testWhenCalledThenWillStoreTheInputValue()
    {
        $object = new Intent('123');
        static::assertEquals('123', (new \ProtectedHelper($object))->getValue('value'));
    }
}
