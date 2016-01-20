<?php
namespace tests\Router\Url;


use MultiRouting\Router\Url;

class UrlWrapperFilterSerialization extends Url
{
    public static function getAvailableSerializations()
    {
        return self::$availableSerializations;
    }
}

class filterSerializationTest extends \PHPUnit_Framework_TestCase
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
        $response = $this->helper->call('filterSerialization', ['']);
        static::assertEquals(Url::DEFAULT_SERIALIZATION, $response);
    }

    /**
     * When the given serialization is allowed then return it.
     *
     * @dataProvider dataProviderAvailableSerializations
     */
    public function testWhenTheGivenSerializationIsAllowedThenReturnIt($serialization)
    {
        $response = $this->helper->call('filterSerialization', [$serialization]);
        static::assertEquals($serialization, $response);
    }

    /**
     * When the given serialization is not allowed then throw exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Serialization Exception: The requested serialization is not available.
     */
    public function testWhenTheGivenVersionIsTheOneSetInTheConstantCURRENTAPIVERSIONThenReturnNull()
    {
        $this->helper->call('filterSerialization', ['web']);
    }

    public function dataProviderAvailableSerializations()
    {
        $wrapInArray = function ($value) {
            return [$value];
        };

        return array_map($wrapInArray, UrlWrapperFilterSerialization::getAvailableSerializations());
    }
}
