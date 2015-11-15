<?php
namespace tests\Router\Matching\IntentValidator;


use MultiRouting\Router\Matching\IntentValidator;
use MultiRouting\Router\Route;

class verifiesTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * When match found return true.
     */
    public function testWhenMatchFoundReturnTrue()
    {
        $intentValue = '/products/{code}/images/{default}/';

        $matching = [
            'code' => '[0-9]+',
            'default' => '[\w]+',
        ];

        $requestIntent = '/products/123/images/blue/';

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getIntent')->once()->andReturn($intentValue);
        $route->shouldReceive('getMatching')->once()->andReturn($matching);

        $validator = \Mockery::mock(__NAMESPACE__ . '\IntentValidatorUnderTestVerifies')->makePartial();
        $validator->shouldAllowMockingProtectedMethods();

        $response = $validator->verifies($route, $requestIntent);
        static::assertTrue($response);
    }

    /**
     * When match not found return false.
     */
    public function testWhenMatchNotFoundReturnFalse()
    {
        $intentValue = '/products/{code}/images/{default}/';

        $matching = [
            'code' => '[0-9]+',
            'default' => '[a-zA-Z]+',
        ];

        $requestIntent = '/products/123/images/0/';

        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();
        $route->shouldReceive('getIntent')->once()->andReturn($intentValue);
        $route->shouldReceive('getMatching')->once()->andReturn($matching);

        $validator = \Mockery::mock(__NAMESPACE__ . '\IntentValidatorUnderTestVerifies')->makePartial();
        $validator->shouldAllowMockingProtectedMethods();

        $response = $validator->verifies($route, $requestIntent);
        static::assertFalse($response);
    }
}

class IntentValidatorUnderTestVerifies extends IntentValidator
{
    public function verifies(Route $route, $requestIntent)
    {
        return parent::verifies($route, $requestIntent);
    }
}
