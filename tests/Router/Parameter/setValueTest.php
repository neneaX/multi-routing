<?php
namespace tests\Router\Parameter;

use MultiRouting\Router\Parameter;

class setValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will store the given value.
     * @dataProvider dataProviderValues
     */
    public function testWhenCalledThenWillStoreTheGivenValue($expected)
    {
        $helper = new \ProtectedHelper(new Parameter('nameme', 'init'));
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
