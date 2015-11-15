<?php
namespace tests\Router\Parameter;

use MultiRouting\Router\Parameter;

class getValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will return the stored value.
     */
    public function testWhenCalledThenWillReturnTheStoredValue()
    {
        $input = new \stdClass();
        $input->name = '/me';

        $object = new Parameter('nameme', $input);
        static::assertSame($input, $object->getValue());
    }
}
