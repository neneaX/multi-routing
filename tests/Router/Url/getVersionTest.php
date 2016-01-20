<?php
namespace tests\Router\Url;


class getVersionTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * The version locked to the CURRENT_API_VERSION value.
     */
    public function testTheVersionLockedToTheCURRENTAPIVERSIONValue()
    {
        $object = \Mockery::mock('\\MultiRouting\\Router\\Url')->makePartial();
        static::assertEquals(CURRENT_API_VERSION, $object->getVersion());
    }
}
