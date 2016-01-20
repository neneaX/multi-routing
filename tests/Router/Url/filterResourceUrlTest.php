<?php
namespace tests\Router\Url;


class filterResourceUrlTest extends \PHPUnit_Framework_TestCase
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
     * When the given url is invalid will throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Resource URL Exception: The requested resource URL is empty.
     * @expectedExceptionCode 400
     */
    public function testWhenTheGivenUrlIsInvalidWillThrowException()
    {
        $this->helper->call('filterResourceUrl', ['']);
    }

    /**
     * When the given url is valid then return value.
     */
    public function testWhenTheGivenUrlIsValidThenReturnValue()
    {
        $validUrlPath = '/valid/call/path/';

        $response = $this->helper->call('filterResourceUrl', [$validUrlPath]);
        static::assertEquals($validUrlPath, $response);
    }
}
