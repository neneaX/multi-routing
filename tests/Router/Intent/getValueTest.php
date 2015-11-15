<?php
namespace tests\Router\Intent;

use MultiRouting\Router\Intent;

class getValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will return the stored value.
     */
    public function testWhenCalledThenWillReturnTheStoredValue()
    {
        $input = new \stdClass();
        $input->name = '/me';

        $object = new Intent($input);
        static::assertSame($input, $object->getValue());
    }
}
