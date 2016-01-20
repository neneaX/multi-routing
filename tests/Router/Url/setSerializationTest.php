<?php
namespace tests\Router\Url;


use MultiRouting\Router\Url;

class setSerializationTest extends \PHPUnit_Framework_TestCase
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
     * When the serialization part is empty then use default serialization and return serialization.
     */
    public function testWhenTheSerializationPartIsEmptyThenUseDefaultSerializationAndReturnSerialization()
    {
        static::assertNull($this->helper->call('setSerialization', ['']));
        static::assertEquals(Url::DEFAULT_SERIALIZATION, $this->helper->getValue('serialization'));
    }

    /**
     * When the given serialization is allowed then return it.
     */
    public function testWhenTheGivenSerializationIsAllowedThenReturnIt()
    {
        static::assertNull($this->helper->call('setSerialization', ['soap']));
        static::assertEquals('soap', $this->helper->getValue('serialization'));
    }

    /**
     * When the given serialization is not allowed then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Serialization Exception: The requested serialization is invalid.
     */
    public function testWhenTheGivenVersionIsTheOneSetInTheConstantCURRENTAPIVERSIONThenReturnNull()
    {
        $this->helper->call('setSerialization', ['web']);
    }

    public function dataProviderAvailableSerializations()
    {
        $wrapInArray = function ($value) {
            return [$value];
        };

        return array_map($wrapInArray, UrlWrapperFilterSerialization::getAvailableSerializations());
    }
}
