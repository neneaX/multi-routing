<?php
namespace tests\Router\Request\Handlers\RpcHandler;


use MultiRouting\Router\Request\Handlers\RpcHandler;

class callTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * If the given controller has the method then return its response as a json encoded result key.
     */
    public function testIfTheGivenControllerHasTheMethodThenReturnItsResponseAsAJsonEncodedResultKey()
    {
        $params = ['b', 'd'];

        $expected = new \stdClass();
        $expected->result = serialize($params);

        $controller = new ControllerUnderTestCall();
        $object = new RpcHandler($controller);

        static::assertEquals(json_encode($expected), $object->getSomething($params));
    }

    /**
     * If the given controller doesnt have the method then return its response as a json encoded result key.
     */
    public function testIfTheGivenControllerDoesntHaveTheMethodThenReturnItsResponseAsAJsonEncodedResultKey()
    {
        $params = ['b', 'd'];

        $expected = new \stdClass();
        $expected->error = 'Method not found';

        $controller = new self();
        $object = new RpcHandler($controller);

        static::assertEquals(json_encode($expected), $object->getMethodResult($params));
    }

    /**
     * If the given controller is invalid then throw exception.
     */
    public function testIfTheGivenControllerIsInvalidThenThrowException()
    {
        $params = ['b', 'd'];

        $expected = new \stdClass();
        $expected->error = 'Controller is invalid';

        $controller = [];
        $object = new RpcHandler($controller);

        static::assertEquals(json_encode($expected), $object->getMethodResult($params));
    }
}

class ControllerUnderTestCall
{
    public function getSomething($parameters)
    {
        return serialize($parameters);
    }
}
