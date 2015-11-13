<?php
namespace MultiRouting\Router\Request\Interpreters;

use IoC\Container as IoC;
use MultiRouting\Helpers\WsdlParser;
use MultiRouting\Router\Intent;
use MultiRouting\Router\Request;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Route;

class SoapInterpreter implements Interpreter
{
    /**
     * @var WsdlParser
     */
    protected $WsdlParser;

    public function __construct()
    {
        $this->setWsdlParser();
    }

    protected function setWsdlParser()
    {
        $this->WsdlParser = IoC::getInstance()->resolve('Helpers\WsdlParser');
    }

    /**
     *
     * @param Request $request            
     * @return Intent
     */
    public function getIntent(Request $request)
    {
        $this->WsdlParser->setRequest($request->getContent());
        
        $calledMethod = $this->WsdlParser->getCalledMethod();
        
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
        $this->WsdlParser->setRequest($request->getContent());

        $calledMethod = $this->WsdlParser->getCalledMethod();
        $calledMethodParams = $this->WsdlParser->getCalledParams();
        
        if ($calledMethod != 'login') {
            // remove the sessionId
            unset($calledMethodParams['Hash']);
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
        $this->WsdlParser->setRequest($request->getContent());

        $calledMethod = $this->WsdlParser->getCalledMethod();
        $calledMethodParams = $this->WsdlParser->getCalledParams();
    
        if ($calledMethod != 'login') {
            // return the sessionId
            return $calledMethodParams['Hash'];
        }
        
        return null;
    }
}