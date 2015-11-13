<?php
namespace MultiRouting\Router\Request\Interpreters;

use MultiRouting\Router\Request;
use MultiRouting\Router\Intent;
use MultiRouting\Router\Route;
use MultiRouting\Router\ParameterCollection;

class RestInterpreter implements Interpreter
{
    /**
     *
     * @param Request $request
     * @return Intent
     */
    public function getIntent(Request $request)
    {
        $resourceUrl = $request->getUrl()->getResourceUrl();
        
        return new Intent($resourceUrl);
    }

    /**
     *
     * @param Route $route
     * @param Request $request
     * @return ParameterCollection
     */
    public function getParameters(Route $route, Request $request)
    {
        $intent = $route->getIntent();
        $matching = $route->getMatching();
        $requestIntent = $request->getUrl()->getResourceUrl();
        
        foreach ($matching as $param => $pattern) {
            $intent = str_replace('{' . $param . '}', '(' . $pattern . ')', $intent);
        }
        $intent = str_replace('/', '\/', $intent);
        $requestIntent = addslashes($requestIntent);
        
        preg_match('/' . $intent . '/', $requestIntent, $matches);
        
        unset($matches[0]);
        
        return new ParameterCollection($matches);
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    public function getSessionId(Request $request)
    {

    }
}
