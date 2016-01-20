<?php
namespace tests\Router\ParameterCollection;


use MultiRouting\Router\Parameter;
use MultiRouting\Router\ParameterCollection;

class constructTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When called without arguments will not store any parameter.
     */
    public function testWhenCalledWithoutArgumentsWillNotStoreAnyParameter()
    {
        $object = new ParameterCollection();
        $helper = new \ProtectedHelper($object);

        static::assertEquals([], $helper->getValue('parameters'));
    }

    /**
     * When called with parameters then store as parameters.
     */
    public function testWhenCalledWithParametersThenStoreAsParameters()
    {
        $parameter1 = new Parameter('a', 'b');
        $parameter2 = new Parameter('c', 'd');

        $object = new ParameterCollection([
            'a' => 'b',
            'c' => 'd',
        ]);

        $helper = new \ProtectedHelper($object);
        $response = $helper->getValue('parameters');

        static::assertEquals(2, count($response));
        static::assertEquals($parameter1, $response['a']);
        static::assertEquals($parameter2, $response['c']);
    }
}
