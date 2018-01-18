<?php
namespace MultiRouting\Adapters\JsonRpc\Matching;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Routing\Route as BaseRoute;
use MultiRouting\Adapters\JsonRpc\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\JsonRpc\Route;

class IntentValidator implements ValidatorInterface
{

    /**
     * Validate a given rule against a route and request.
     *
     * @param BaseRoute $route
     * @param Request $request
     *
     * @return bool
     */
    public function matches(BaseRoute $route, Request $request)
    {
        if (! $route instanceof Route) {
            return false;
        }
        return $route->getIntent() === ($route->getInterpreter($request)->getIntent());
    }
}
