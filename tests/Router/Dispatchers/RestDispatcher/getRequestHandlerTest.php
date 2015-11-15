<?php
namespace tests\Router\Dispatchers\RestDispatcher;


use IoC\Container;
use MultiRouting\Router\Dispatchers\RestDispatcher;

/**
 * @preserveGlobalState false
 */
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
        $object = new RestDispatcher();

        $helper = new \ProtectedHelper($object);
        $controller = new \stdClass();

        try {
            $helper->call('getRequestHandler', [$controller]);
            static::fail();

        } catch (\Exception $e) {
            static::assertEquals('Exception', get_class($e));
            static::assertEquals('No class found registered with that name: Router\Request\Handler', $e->getMessage());
        }
    }

    /**
     * When the handler for this rest controller is set in the dependency container then return it.
     */
    public function testWhenTheHandlerForThisRestControllerIsSetInTheDependencyContainerThenReturnIt()
    {
        $expected = new \stdClass();

        $object = new RestDispatcher();
        $helper = new \ProtectedHelper($object);
        $controller = new \stdClass();

        Container::getInstance()->register('Router\Request\Handler', function ($serialization, $paramController) use ($expected, $controller) {
            if ($serialization == 'rest' && $paramController === $controller) {
                return $expected;
            }
        });

        $response = $helper->call('getRequestHandler', [$controller]);
        static::assertSame($expected, $response);
    }
}
