<?php
namespace MultiRouting\Adapters\JsonRpc\Matching;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Routing\Route;
use MultiRouting\Adapters\JsonRpc\Request\Interpreters\JsonRpcInterpreter;

class IntentValidator implements ValidatorInterface
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
        return $route->getIntent() === (new JsonRpcInterpreter($request))->getIntent();
    }
}
