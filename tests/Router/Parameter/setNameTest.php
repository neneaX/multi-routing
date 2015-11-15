<?php
namespace tests\Router\Parameter;

use MultiRouting\Router\Parameter;

class setNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * When called then will store the given name.
     * @dataProvider dataProviderNames
     */
    public function testWhenCalledThenWillStoreTheGivenName($expected)
    {
        $helper = new \ProtectedHelper(new Parameter('nameme', 'init'));
        $helper->call('setName', [$expected]);

        static::assertSame($expected, $helper->getValue('name'));
    }

    public function dataProviderNames()
    {
        return [
            [''],
            ['123'],
            [[]],
            [new \stdClass],
        ];
    }
}
