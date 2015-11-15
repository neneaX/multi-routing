<?php
namespace MultiRouting\Router\Request\Interpreters;

use IoC\Container as IoC;
use MultiRouting\Helpers\JsonParser;
use MultiRouting\Router\Intent;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Request;
use MultiRouting\Router\Route;

class RpcInterpreter implements Interpreter
{

    /**
     *
     * @var JsonParser
     */
    protected $JsonParser;

    public function __construct()
    {
        $this->setJsonParser();
    }

    protected function setJsonParser()
    {
        $this->JsonParser = IoC::getInstance()->resolve('Helpers\JsonParser');
    }

    /**
     *
     * @param Request $request            
     * @return Intent
     */
    public function getIntent(Request $request)
    {
        $this->JsonParser->setRequest($request->getContent());

        $calledMethod = $this->JsonParser->getCalledMethod();

        return new Intent($calledMethod);
    }

    /**
     *
     * @param Route $route
     * @param Request $request
     * @return ParameterCollection
     */
    public function getParameters(Route $route, Request $request)
    {
        $this->JsonParser->setRequest($request->getContent());

        $calledMethod = $this->JsonParser->getCalledMethod();
        $calledMethodParams = $this->JsonParser->getCalledParams();
        
        if ($calledMethod != 'login') {
            // remove the sessionId
            array_shift($calledMethodParams);
        }
        
        return new ParameterCollection($calledMethodParams);
    }
    
    /**
     * 
     * @param Request $request
     * @return string
     */
    public function getSessionId(Request $request)
    {
        $this->JsonParser->setRequest($request->getContent());

        $calledMethod = $this->JsonParser->getCalledMethod();
        $calledMethodParams = $this->JsonParser->getCalledParams();
        
        if ($calledMethod != 'login') {
            // return the sessionId
            return array_shift($calledMethodParams);
        }
        
        return null;
    }
}