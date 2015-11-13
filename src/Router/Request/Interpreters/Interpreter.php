<?php
namespace MultiRouting\Router\Request\Interpreters;

use MultiRouting\Router\Request;
use MultiRouting\Router\Intent;
use MultiRouting\Router\ParameterCollection;
use MultiRouting\Router\Route;

interface Interpreter
{

    /**
     *
     * @param Request $request            
     * @return Intent
     */
    public function getIntent(Request $request);

    /**
     *
     * @param Route $route
     * @param Request $request            
     * @return ParameterCollection
     */
    public function getParameters(Route $route, Request $request);
    
    /**
     *
     * @param Request $request
     * @return string
     */
    public function getSessionId(Request $request);
    
}