<?php
namespace tests\Router\Intent;

use MultiRouting\Router\Intent;

class setValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will store the given value.
     * @dataProvider dataProviderValues
     */
    public function testWhenCalledThenWillStoreTheGivenValue($expected)
    {
        $helper = new \ProtectedHelper(new Intent('init'));
        $helper->call('setValue', [$expected]);

        static::assertSame($expected, $helper->getValue('value'));
    }

    public function dataProviderValues()
    {
        return [
            [''],
            ['123'],
            [[]],
            [new \stdClass],
        ];
    }
}
