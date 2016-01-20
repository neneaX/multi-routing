<?php
namespace tests\Router\Request\Interpreters\SoapInterpreter;


use IoC\Container;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Request\Interpreters\SoapInterpreter;

class getParametersTest extends \PHPUnit_Framework_TestCase
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
     * Will return the parameter as a collection without the first parameter if the method is not login.
     */
    public function testWillReturnTheParameterAsACollectionWithoutTheFirstParameterIfTheMethodIsNotLogin()
    {
        $this->calledMethodOriginalParams = [
            'Hash' => 'abc000111222',
            'ProductName' => 'AVT',
            'Version' => '1.0.1',
        ];

        $this->calledMethodParams = [
            'ProductName' => 'AVT',
            'Version' => '1.0.1',
        ];

        $this->calledMethodName = 'updateProduct';

        static::assertEquals(new ParameterCollection($this->calledMethodParams), $this->invokeAndReturn());
    }

    /**
     * When method is login then don't remove the fist parameter.
     */
    public function testWhenMethodIsLoginThenDonTRemoveTheFistParameter()
    {
        $this->calledMethodOriginalParams = [
            'Code' => 'ABCD',
            'Date' => '2015-01-01',
            'Hashed' => 'qwertyuytrewq',
        ];

        $this->calledMethodParams = $this->calledMethodOriginalParams;

        $this->calledMethodName = 'login';

        static::assertEquals(new ParameterCollection($this->calledMethodParams), $this->invokeAndReturn());
    }

    /**
     * @return ParameterCollection
     */
    protected function invokeAndReturn()
    {
        $route = \Mockery::mock('\MultiRouting\Router\Route')->makePartial();

        $request = \Mockery::mock('\MultiRouting\Router\Request')->makePartial();
        $request->shouldReceive('getContent')->once()->andReturn($this->requestContent);

        $instance = \Mockery::mock('\MultiRouting\Helpers\WsdlParser')->makePartial();
        $instance->shouldReceive('setRequest')->once()->with($this->requestContent);
        $instance->shouldReceive('getCalledMethod')->once()->andReturn($this->calledMethodName);
        $instance->shouldReceive('getCalledParams')->once()->andReturn($this->calledMethodOriginalParams);

        Container::getInstance()->register('Helpers\WsdlParser', $instance);

        $object = new SoapInterpreter();
        $response = $object->getParameters($route, $request);

        return $response;
    }
}
