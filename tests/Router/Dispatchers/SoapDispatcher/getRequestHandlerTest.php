<?php
namespace tests\Router\Dispatchers\SoapDispatcher;


use IoC\Container;
use MultiRouting\Router\Dispatchers\SoapDispatcher;

class getRequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Container::reset();
    }

    public function tearDown()
    {
        \Mockery::close();
        Container::reset();
    }

    /**
     * When the class is not set in the dependency container then throw exception.
     */
    public function testWhenTheClassIsNotSetInTheDependencyContainerThenThrowException()
    {
        $object = new SoapDispatcher();

        $helper = new \ProtectedHelper($object);
        $controller = new \stdClass();

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
        $controller = new \stdClass();

        Container::getInstance()->register('Router\Request\Handler', function ($serialization, $paramController) use ($expected, $controller) {
            if ($serialization == 'soap' && $paramController === $controller) {
                return $expected;
            }
        });

        $object = new SoapDispatcher();
        $helper = new \ProtectedHelper($object);

        $response = $helper->call('getRequestHandler', [$controller]);
        static::assertSame($expected, $response);
    }
}
