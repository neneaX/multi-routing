<?php
namespace MultiRouting\Router\Matching;

use MultiRouting\Router\Request;
use MultiRouting\Router\Route;
use MultiRouting\Router\Request\Interpreters\Interpreter as RequestInterpreter;
use IoC\Container as IoC;

class IntentValidator implements Validator
{

    /**
     * Validate a given rule against a route and request.
     *
     * @param Route $route            
     * @param Request $request            
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        $serialization = $request->getUrl()->getSerialization();
        $requestInterpreter = $this->getRequestInterpreter($serialization);
        
        return $this->verifies($route, $requestInterpreter->getIntent($request)->getValue());
    }
    
    /**
     * 
     * @param string $serialization
     * @return RequestInterpreter
     */
    protected function getRequestInterpreter($serialization)
    {
        return IoC::getInstance()->resolve('Router\Request\Interpreter', [$serialization]);
    }
    
    /**
     * @param Route $route
     * @param       $requestIntent
     *
     * @return bool
     */
    protected function verifies(Route $route, $requestIntent)
    {
        $intent = $route->getIntent();
        $matching = $route->getMatching();
        
        foreach ($matching as $param => $pattern) {
            $intent = str_replace('{' . $param . '}', '(' . $pattern . ')', $intent);
        }
        $intent = str_replace('/', '\/', $intent);
        $requestIntent = addslashes($requestIntent);
        preg_match('/' . $intent . '/', $requestIntent, $matches);
        
        if (!empty($matches)) {
            return true;
        }
        return false;
    }
    
}
