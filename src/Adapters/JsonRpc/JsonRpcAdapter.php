<?php
namespace MultiRouting\Adapters\JsonRpc;

use MultiRouting\Adapters\Adapter;
use MultiRouting\Router;

class JsonRpcAdapter implements Adapter
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string
     */
    protected $currentIntent;

    /**
     * JsonRpcAdapter constructor.
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
     * @return JsonRpcRoute
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
     * @return JsonRpcRoute
     */
    public function buildRoute($methods, $uri, $action)
    {
        $route = new JsonRpcRoute($methods, $uri, $action);
        $route->setIntent($this->currentIntent);

        return $route;
    }
}