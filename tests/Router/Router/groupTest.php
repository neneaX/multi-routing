<?php
namespace tests\Router\Router;

use MultiRouting\Router\Router;

/**
 * @note different style
 * @note this feels incomplete. @todo add an example of a callback that appends multiple routes to the router.
 */
class groupTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will call the closure after temporary setting the parameters serialization or array middleware.
     */
    public function testWillCallTheClosureAfterTemporarySettingTheParametersSerializationOrArrayMiddlewareAndKeepTheSerializationIfValid()
    {
        $router = new RouterUnderTestGroup();

        $params = [
            'serialization' => 'soap',
            'middleware' => ['CheckAuth'],
        ];

        $spy = \Mockery::mock('CallbackListener')->makePartial();
        $spy->shouldReceive('foundEvent')->once()->with('callback.group.launched');

        $callback = function () use ($spy, $router) {
            static::assertEquals('soap', $router::getSerialization());
            static::assertEquals(['CheckAuth'], $router::getMiddleware());
            $spy->foundEvent('callback.group.launched');
        };

        $response = $router::group($params, $callback);
        static::assertNull($response);

        static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization()); /** @note the serialization is nulled after the call and will default as rpc on next getSerialization call */
        static::assertNull($router::getMiddleware());
    }

    /**
     * Will call the closure after ignoring the serialization if the parameter serialization is not valid/allowed.
     */
    public function testWillCallTheClosureAfterIgnoringTheSerializationIfTheParameterSerializationIsNotValidAllowed()
    {
        $router = new RouterUnderTestGroup();
        $router::group('rest', function () {});

        $params = [
            'serialization' => 'jax',
            'middleware' => ['CheckAuth'],
        ];

        $spy = \Mockery::mock('CallbackListener')->makePartial();
        $spy->shouldReceive('foundEvent')->once()->with('callback.group.launched');

        $callback = function () use ($spy, $router) {
            static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization());
            static::assertEquals(['CheckAuth'], $router::getMiddleware());
            $spy->foundEvent('callback.group.launched');
        };

        $response = $router::group($params, $callback);
        static::assertNull($response);

        static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization()); // @note the serialization remains set if is a valid / allowed serialization.
        static::assertNull($router::getMiddleware());
    }

    /**
     * Will call the closure after temporary setting the parameters serialization or middleware.
     */
    public function testWillCallTheClosureAfterTemporarySettingTheParametersSerializationOrMiddleware()
    {
        $router = new RouterUnderTestGroup();

        $params = [
            'serialization' => 'soap',
            'middleware' => 'CheckLogin',
        ];

        $spy = \Mockery::mock('CallbackListener')->makePartial();
        $spy->shouldReceive('foundEvent')->once()->with('callback.group.launched');

        $callback = function () use ($spy, $router) {
            static::assertEquals('soap', $router::getSerialization());
            static::assertEquals(['CheckLogin'], $router::getMiddleware());
            $spy->foundEvent('callback.group.launched');
        };

        $response = $router::group($params, $callback);
        static::assertNull($response);

        static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization());
        static::assertNull($router::getMiddleware());
    }

    /**
     * Will call the closure after temporary setting the default serialization and parameter middleware.
     */
    public function testWillCallTheClosureAfterTemporarySettingTheDefaultSerializationAndParameterMiddleware()
    {
        $router = new RouterUnderTestGroup();

        $params = [
            'middleware' => 'CheckLogin',
        ];

        $spy = \Mockery::mock('CallbackListener')->makePartial();
        $spy->shouldReceive('foundEvent')->once()->with('callback.group.launched');

        $callback = function () use ($spy, $router) {
            static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization());
            static::assertEquals(['CheckLogin'], $router::getMiddleware());
            $spy->foundEvent('callback.group.launched');
        };

        $response = $router::group($params, $callback);
        static::assertNull($response);

        static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization());
        static::assertNull($router::getMiddleware());
    }

    /**
     * Will call the closure after setting the seralization and no middleware when no middleware is sent in the parameters..
     */
    public function testWillCallTheClosureAfterSettingTheSeralizationAndNoMiddlewareWhenNoMiddlewareIsSentInTheParameters()
    {
        $router = new RouterUnderTestGroup();

        $params = [
            'serialization' => 'rest',
        ];

        $spy = \Mockery::mock('CallbackListener')->makePartial();
        $spy->shouldReceive('foundEvent')->once()->with('callback.group.launched');

        $callback = function () use ($spy, $router) {
            static::assertEquals('rest', $router::getSerialization());
            static::assertEquals([], $router::getMiddleware());
            $spy->foundEvent('callback.group.launched');
        };

        $response = $router::group($params, $callback);
        static::assertNull($response);

        static::assertEquals(Router::DEFAULT_SERIALIZATION, $router::getSerialization());
        static::assertNull($router::getMiddleware());
    }
}

class RouterUnderTestGroup extends Router
{
    public static function getSerialization()
    {
        return parent::getSerialization();
    }

    public static function getMiddleware()
    {
        return parent::getMiddleware();
    }
}
