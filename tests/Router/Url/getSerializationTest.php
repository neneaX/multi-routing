<?php
namespace tests\Router\Url;


class getSerializationTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $helper;

    public function setUp()
    {
        $this->object = \Mockery::mock('\\MultiRouting\\Router\\Url')->makePartial();
        $this->helper = new \ProtectedHelper($this->object);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Return set serialization value.
     */
    public function testReturnSetSerializationValue()
    {
        $expected = 'jsonrpc';
        $this->helper->setValue('serialization', $expected);
        static::assertSame($expected, $this->object->getSerialization());
    }
}
