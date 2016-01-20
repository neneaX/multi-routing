<?php
namespace tests\Router\Dispatchers\SoapDispatcher;


use IoC\Container;
use MultiRouting\Router\Dispatchers\SoapDispatcher;

class getRequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When the class is not set in the dependency container then throw exception.
     */
    public function testWhenTheClassIsNotSetInTheDependencyContainerThenThrowException()
    {
        $object = new SoapDispatcher();

        $helper = new \ProtectedHelper($object);
        $controller = new \stdClass();

        ContainerUnderTest::setInstance(null);

        try {
            $helper->call('getRequestHandler', [$controller]);
            static::fail();

        } catch (\Exception $e) {
            static::assertEquals('No class found registered with that name: Router\Request\Handler', $e->getMessage());
        }
    }

    /**
     * When the handler for this soap controller is set in the dependency container then return it.
     */
    public function testWhenTheHandlerForThisSoapControllerIsSetInTheDependencyContainerThenReturnIt()
    {
        $expected = new \stdClass();

        $object = new SoapDispatcher();
        $helper = new \ProtectedHelper($object);
        $controller = new \stdClass();

        $containerMock = \Mockery::mock('\IoC\Container')->makePartial();
        $containerMock->shouldAllowMockingProtectedMethods();
        $containerMock->shouldReceive('resolve')
            ->once()
            ->with('Router\Request\Handler', ['soap', $controller]) // if fail debug here and check passed i/o.
            ->andReturn($expected);

        ContainerUnderTest::setInstance($containerMock);

        $response = $helper->call('getRequestHandler', [$controller]);
        static::assertSame($expected, $response);
    }
}

class ContainerUnderTest extends Container
{
    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }
}
