<?php
namespace MultiRouting;

use \Illuminate\Routing\RouteCollection as BaseRouteCollection;

class RouteCollection extends BaseRouteCollection
{
    /**
     * Add the given route to the arrays of routes.
     *
     * @param Route $route
     * @return void
     */
    protected function addToCollections($route)
    {
        $collectionIdentifier = $route->getCollectionIdentifier();

        foreach ($route->methods() as $method)
        {
            $this->routes[$method][$collectionIdentifier] = $route;
        }

        $this->allRoutes[$method.$collectionIdentifier] = $route;
    }
}