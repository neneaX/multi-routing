<?php
namespace MultiRouting\Adapters\Soap;

use MultiRouting\Adapters\AdapterInterface;
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
     * @var string
     */
    protected $currentWsdlPath;

    /**
     * Adapter constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $wsdlPath
     * @return $this
     */
    public function wsdl($wsdlPath)
    {
        $this->currentWsdlPath = $wsdlPath;

        return $this;
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

        return $this->router->post($uri, $action);
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
        $route->setWsdl($this->currentWsdlPath);

        return $route;
    }
}