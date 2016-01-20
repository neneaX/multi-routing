<?php
namespace tests\Router\ParameterCollection;


use MultiRouting\Router\Parameter;
use MultiRouting\Router\ParameterCollection;

class getTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When parameter is found then return.
     */
    public function testWhenParameterIsFoundThenReturn()
    {
        $parameter2 = new Parameter('c', 'd');

        $object = new ParameterCollection([
            'a' => 'b',
            'c' => 'd',
        ]);

        $parameterResponse = $object->get('c');
        static::assertEquals($parameter2, $parameterResponse);
    }

    /**
     * When parameter is not found then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Parameter not found
     */
    public function testWhenParameterIsNotFoundThenThrowException()
    {
        $object = new ParameterCollection([
            'a' => 'b',
            'c' => 'd',
        ]);

        $object->get('f');

        static::fail('Expected an exception.');
    }
}
