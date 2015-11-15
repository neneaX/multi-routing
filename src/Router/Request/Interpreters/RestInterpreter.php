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
     * @param Route $route
     * @param Request $request
     * @return ParameterCollection
     */
    public function getParameters(Route $route, Request $request)
    {
        $intent = $route->getIntent();
        $matching = $route->getMatching();
        $requestIntent = $request->getUrl()->getResourceUrl();

        // prepare matching pattern
        foreach ($matching as $param => $pattern) {
            $intent = str_replace('{' . $param . '}', '(' . $pattern . ')', $intent);
        }

        // match intent
        $intent = str_replace('/', '\/', $intent);
        $requestIntent = addslashes($requestIntent);

        preg_match('/' . $intent . '/', $requestIntent, $matches);
        unset($matches[0]); // remove full string match, keep metched parameters

        // map parameters using matching labels
        $parameters = [];
        $cursor = 0;

        foreach ($matching as $param => $pattern) {
            $cursor++; // here because index 0 was removed (full string match)
            $parameters[$param] = $matches[$cursor];
        }

        return new ParameterCollection($parameters);
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
