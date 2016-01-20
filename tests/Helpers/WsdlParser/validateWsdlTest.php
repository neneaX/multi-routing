<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class validateWsdlTest extends \PHPUnit_Framework_TestCase
{
    /** @var  WsdlParser */
    protected $parser;

    public function setUp()
    {
        $this->parser = new WsdlParser();
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When wsdl attribute is not set then throw exception.
     * @dataProvider dataProviderEmptyValues
     *
     * @expectedException \Exception
     * @expectedExceptionMessage WSDL not set
     */
    public function testWhenWsdlAttributeIsNotSetThenThrowException($empty)
    {
        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('wsdl', $empty);

        $helper->call('validateWsdl');
    }

    public function dataProviderEmptyValues()
    {
        return [
            [null],
            [''],
            [[]],
            // [new \stdClass()],
        ];
    }

    /**
     * When wsdl attribute is set then return null.
     */
    public function testWhenWsdlAttributeIsSetThenReturnNull()
    {
        $helper = new \ProtectedHelper($this->parser);
        $helper->setValue('wsdl', new \stdClass);

        static::assertNull($helper->call('validateWsdl'));
    }
}
