<?php
namespace tests\Router\Request\Interpreters\SoapInterpreter;


use IoC\Container;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Request\Interpreters\SoapInterpreter;

class getSessionIdTest extends \PHPUnit_Framework_TestCase
{
    protected $calledMethodOriginalParams;
    protected $calledMethodParams;
    protected $calledMethodName;
    protected $requestContent = 'ignore-content-soap';

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * Will return the hash id found in the first parameter when method is not login.
     */
    public function testWillReturnTheHashIdFoundInTheFirstParameterWhenMethodIsNotLogin()
    {
        $this->calledMethodOriginalParams = [
            'Hash' => 'abc000111222',
            'ProductName' => 'AVT',
            'Version' => '1.0.1',
        ];

        $this->calledMethodName = 'updateProduct';

        static::assertEquals('abc000111222', $this->invokeAndReturn());
    }

    /**
     * When method is login then return null.
     */
    public function testWhenMethodIsLoginThenReturnNull()
    {
        $this->calledMethodOriginalParams = [
            'Code' => 'ABCD',
            'Date' => '2015-01-01',
            'Hashed' => 'qwertyuytrewq',
        ];

        $this->calledMethodName = 'login';

        static::assertEquals(null, $this->invokeAndReturn());
    }

    /**
     * @return string
     */
    protected function invokeAndReturn()
    {
        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getContent')->once()->andReturn($this->requestContent);

        $instance = \Mockery::mock('\MultiRouting\Helpers\WsdlParser')->makePartial();
        $instance->shouldReceive('setRequest')->once()->with($this->requestContent);
        $instance->shouldReceive('getCalledMethod')->once()->andReturn($this->calledMethodName);
        $instance->shouldReceive('getCalledParams')->once()->andReturn($this->calledMethodOriginalParams);

        Container::getInstance()->register('Helpers\WsdlParser', $instance);

        $object = new SoapInterpreter();
        $response = $object->getSessionId($request);

        return $response;
    }
}
