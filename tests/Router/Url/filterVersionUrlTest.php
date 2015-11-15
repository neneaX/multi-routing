<?php
namespace tests\Router\Url;


class filterVersionTest extends \PHPUnit_Framework_TestCase
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
     * When the given version is empty will throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Version Exception: Version cannot be empty.
     * @expectedExceptionCode 400
     */
    public function testWhenTheGivenVersionIsEmptyWillThrowException()
    {
        $this->helper->call('filterVersion', ['']);
    }

    /**
     * When the given version is not the constant CURRENT_API_VERSION will throw exception.
     *
     * @expectedException \MultiRouting\Router\Exceptions\VersionException
     * @expectedExceptionMessage Version Exception: Invalid requested version: 19.95
     */
    public function testWhenTheGivenUrlIsValidThenReturnValue()
    {
        $this->helper->call('filterVersion', ['19.95']);
    }

    /**
     * When the given version is the one set in the constant CURRENT_API_VERSION then return null.
     */
    public function testWhenTheGivenVersionIsTheOneSetInTheConstantCURRENTAPIVERSIONThenReturnNull()
    {
        static::assertNull($this->helper->call('filterVersion', [CURRENT_API_VERSION]));
    }
}
