<?php
namespace MultiRouting;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use MultiRouting\Adapters\AdapterInterface;
use MultiRouting\Adapters\Main\Adapter as MainAdapter;

class Router extends \Illuminate\Routing\Router
{

    /**
     * @var AdapterService
     */
    protected $adapterService;

    /**
     * @var AdapterInterface
     */
    protected $currentAdapter;

    /**
     * Router constructor.
     * @param Dispatcher $events
     * @param Container $container
     */
    public function __construct(Dispatcher $events, Container $container)
    {
        parent::__construct($events, $container);

        $this->setRoutes(new RouteCollection());
        $this->adapterService = new AdapterService($this->container);
        $this->setDefaultAdapter(MainAdapter::name);
    }

    /**
     * Register a custom routing adapter
     *
     * @param string $adapterName
     * @param string $class
     */
    public function registerAdapter($adapterName, $class)
    {
        $this->adapterService->registerAdapter($adapterName, $class);
    }

    /**
     * Allow registered routing adapters to be used
     *
     * @param array $adapters
     */
    public function allowAdapters(array $adapters = [])
    {
        foreach ($adapters as $adapterName) {
            $this->adapterService->allowAdapter($adapterName);
        }
    }

    /**
     * @param string $adapterName
     */
    public function setDefaultAdapter($adapterName)
    {
        $this->adapterService->setDefaultAdapter($adapterName, $this);
    }

    /**
     * @param $adapterName
     * @return AdapterInterface
     */
    public function adapter($adapterName)
    {
        return $this->adapterService->useAdapter($adapterName, $this);
    }

    /**
     * Add a route to the underlying route collection.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string  $action
     * @return \Illuminate\Routing\Route
     */
    protected function addRoute($methods, $uri, $action)
    {
        $route = parent::addRoute($methods, $uri, $action);

        $this->adapterService->resetAdapter();

        return $route;
    }

    /**
     * @param array|string $methods
     * @param string $uri
     * @param mixed $action
     * @return Route|mixed
     */
    protected function newRoute($methods, $uri, $action)
    {
        return $this->adapterService
            ->getAdapterInUse()
            ->buildRoute($methods, $uri, $action)
            ->setContainer($this->container);
    }

}