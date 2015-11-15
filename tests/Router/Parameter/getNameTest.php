<?php
namespace tests\Router\Parameter;

use MultiRouting\Router\Parameter;

class getNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will return the stored name.
     */
    public function testWhenCalledThenWillReturnTheStoredName()
    {
        $input = 'name/me';

        $object = new Parameter($input, 'valueme');
        static::assertSame($input, $object->getName());
    }
}
