<?php
namespace tests\Router\Helpers\WsdlParser;

use MultiRouting\Helpers\WsdlParser;

class setWsdlTest extends \PHPUnit_Framework_TestCase
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
     * If the given file does not exist then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage The path does not exist.
     */
    public function testIfTheGivenFileDoesNotExistThenThrowException()
    {
        $file = '/path/invalid.file';
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($file)->andReturn(false);

        $this->parser->setWsdl($file);
    }

    /**
     * If the given file exists then set path to wsdl attribute..
     */
    public function testIfTheGivenFileExistsThenSetPathToWsdlAttribute()
    {
        $file = '/path/valid/file.wsdl';
        \Registry::get('system.mock')->shouldReceive('file_exists')->once()->with($file)->andReturn(true);

        $this->parser->setWsdl($file);

        $helper = new \ProtectedHelper($this->parser);
        static::assertEquals($file, $helper->getValue('wsdl'));
    }
}
