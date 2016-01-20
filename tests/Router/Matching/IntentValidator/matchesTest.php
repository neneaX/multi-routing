<?php
namespace tests\Router\Matching\IntentValidator;


class matchesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will always return true.
     */
    public function testWillAlwaysReturnTrue()
    {
        $serialization = 'soap';
        $intentValue = 'intentValue';

        $urlMock = \Mockery::mock('Url')->makePartial();
        $urlMock->shouldReceive('getSerialization')->once()->andReturn($serialization);

        $intent = \Mockery::mock('\MultiRouting\Router\Intent')->makePartial();
        $intent->shouldReceive('getValue')->once()->andReturn($intentValue);

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getUrl')->once()->andReturn($urlMock);

        $requestInterpreter = \Mockery::mock('RequestInterpreter')->makePartial();
        $requestInterpreter->shouldReceive('getIntent')->once()->with($request)->andReturn($intent);

        \IoC\Container::getInstance()->register('Router\Request\Interpreter', function ($serialization) use ($requestInterpreter) {
            if ($serialization === 'soap') {
                return $requestInterpreter;
            }
        });

        $validator = \Mockery::mock('\MultiRouting\Router\Matching\IntentValidator')->makePartial();
        $validator->shouldAllowMockingProtectedMethods();
        $validator->shouldReceive('verifies')->once()->with($route, $intentValue)->andReturn(true);

        static::assertTrue($validator->matches($route, $request));
    }
}
