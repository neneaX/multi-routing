<?php
namespace tests\Router\ParameterCollection;


use MultiRouting\Router\ParameterCollection;

class rewindTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will return null.
     */
    public function testWillReturnNull()
    {
        $object = new ParameterCollection(['productCode' => 'aaa']);
        static::assertNull($object->rewind());
    }
}