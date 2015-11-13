<?php

namespace MultiRouting\Middleware;

use MultiRouting\Router\Route;
use MultiRouting\Router\Request;

interface Middleware
{
    
    /**
     * Handle an incoming request
     *
     * @param Route $route
     * @param Request $request
     * @param \Closure $callback
     * @return mixed
     */
    public function handle(Route $route, Request $request, \Closure $callback = null);
}