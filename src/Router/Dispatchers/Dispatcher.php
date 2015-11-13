<?php
namespace MultiRouting\Router\Dispatchers;

use MultiRouting\Router\Route;
use MultiRouting\Router\Request;

interface Dispatcher
{
    /**
     * @param Route $route
     * @param Request $request
     */
    public function dispatch(Route $route, Request $request);
}
