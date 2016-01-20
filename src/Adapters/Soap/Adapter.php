<?php
namespace MultiRouting\Adapters\Soap;

use MultiRouting\Adapters\Adapter as AdapterInterface;
use MultiRouting\Router;

class Adapter implements AdapterInterface
{

    /**
     * The adapter name used for registering and implementing other functionality
     */
    const name = 'Soap';

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $currentIntent;

    /**
     * Adapter constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $uri
     * @param $intent
     * @param $action
     * @return Route
     */
    public function intent($uri, $intent, $action)
    {
        $this->currentIntent = $intent;

        $route = $this->router->post($uri, $action);

        $this->router->stopUsingAdapter();

        return $route;
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
        $route->setIntent($this->currentIntent);

        return $route;
    }
}