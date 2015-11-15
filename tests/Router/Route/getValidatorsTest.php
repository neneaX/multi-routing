<?php
namespace tests\Router\Route;


use MultiRouting\Router\Route;

class getValidatorsTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Bind has a fluent interface.
     */
    public function testBindHasAFluentInterface()
    {
        $serialization = 'rest';

        $httpMethod = 'get';

        $intent = '/orders/{refNo}/';

        $action = '/orders/123/';

        $matching = [
            'refNo' => '[\d]+'
        ];

        $middleware = [
            'ClassOne',
            'ClassTwo',
        ];

        $route = new Route($serialization, $httpMethod, $intent, $action, $matching, $middleware);
        $helpers = new \ProtectedHelper($route);

        $response = $helpers->call('getValidators');

        static::assertTrue(count($response) > 0);

        foreach ($response as $validator) {
            static::assertInstanceOf('\MultiRouting\Router\Matching\Validator', $validator);
        }
    }
}
