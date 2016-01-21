<?php
namespace MultiRouting\Adapters\Main;

use MultiRouting\Router;
use MultiRouting\Adapters\AdapterInterface;

class Adapter implements AdapterInterface
{

    /**
     * The adapter name used for registering and implementing other functionality
     */
    const name = 'Main';

    /**
     * @var Router
     */
    protected $router;

    /**
     * JsonRpcAdapter constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $methods
     * @param $uri
     * @param $action
     * @return Route
     */
    public function buildRoute($methods, $uri, $action)
    {
        $route = new Route($methods, $uri, $action);

        return $route;
    }
}