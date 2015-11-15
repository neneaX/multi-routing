<?php
namespace tests\Router\Route;


class matchesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When no validators are found then return true.
     */
    public function testWhenNoValidatorsAreFoundThenReturnTrue()
    {
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldAllowMockingProtectedMethods();
        $route->shouldReceive('getValidators')->once()->andReturn([]);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();

        static::assertTrue($route->matches($request));
    }

    /**
     * When validators are found and they all validate then return true.
     */
    public function testWhenValidatorsAreFoundAndTheyAllValidateThenReturnTrue()
    {
        $validator1 = \Mockery::mock('\MultiRouting\Router\Matching\MethodValidator')->makePartial();
        $validator2 = \Mockery::mock('\MultiRouting\Router\Matching\SerializationValidator')->makePartial();

        $validators = [
            $validator1,
            $validator2,
        ];

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldAllowMockingProtectedMethods();
        $route->shouldReceive('getValidators')->once()->andReturn($validators);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $validator1->shouldReceive('matches')->once()->with($route, $request)->andReturn(true);
        $validator2->shouldReceive('matches')->once()->with($route, $request)->andReturn(true);

        static::assertTrue($route->matches($request));
    }

    /**
     * When validators are found and at least one doesnt validate then return false.
     */
    public function testWhenValidatorsAreFoundAndAtLeastOneDoesntValidateThenReturnFalse()
    {
        $validator1 = \Mockery::mock('\MultiRouting\Router\Matching\MethodValidator')->makePartial();
        $validator2 = \Mockery::mock('\MultiRouting\Router\Matching\SerializationValidator')->makePartial();

        $validators = [
            $validator1,
            $validator2,
        ];

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldAllowMockingProtectedMethods();
        $route->shouldReceive('getValidators')->once()->andReturn($validators);

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $validator1->shouldReceive('matches')->once()->with($route, $request)->andReturn(true);
        $validator2->shouldReceive('matches')->once()->with($route, $request)->andReturn(false);

        static::assertFalse($route->matches($request));
    }
}
