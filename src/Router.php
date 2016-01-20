<?php
namespace MultiRouting;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Routing\Route;
use MultiRouting\Adapters\Adapter;

class Router extends \Illuminate\Routing\Router
{

    /**
     * @var AdapterService
     */
    protected $adapterService;

    /**
     * Flag for determining whether an adapter is in use for the current task or not
     * This is necessary for processing the basic logic or switching to adapter specific logic
     *
     * Possible task examples: mapping a route, dispatching a request etc.
     *
     * @var bool
     */
    protected $adapterInUse = false;

    /**
     * @var Adapter
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

        $this->adapterService = new AdapterService($this->container);
    }

    /**
     * Register a custom routing adapter
     *
     * @param $name
     * @param $class
     */
    public function registerAdapter($name, $class)
    {
        $this->adapterService->registerAdapter($name, $class);
    }

    /**
     * Allow registered routing adapters to be used
     *
     * @param array $adapters
     */
    public function useAdapters(array $adapters = [])
    {
        foreach ($adapters as $adapterName) {
            $this->adapterService->allowAdapter($adapterName);
        }
    }

    /**
     * @param $adapterName
     * @return Adapter
     */
    public function adapter($adapterName)
    {
        $adapter = $this->adapterService->getAdapter($adapterName, $this);
        $this->startUsingAdapter($adapter);

        return $this->currentAdapter;
    }

    /**
     * Start using a given adapter
     *
     * @param Adapter $adapter
     */
    protected function startUsingAdapter(Adapter $adapter)
    {
        $this->currentAdapter = $adapter;
        $this->adapterInUse = true;
    }

    /**
     * Stop using an adapter
     */
    public function stopUsingAdapter()
    {
        $this->adapterInUse = false;
    }

    /**
     * @param array|string $methods
     * @param string $uri
     * @param mixed $action
     * @return Route|mixed
     */
    protected function newRoute($methods, $uri, $action)
    {
        if (true === $this->adapterInUse) {
            $route = $this->currentAdapter->buildRoute($methods, $uri, $action);
        } else {
            $route = new Route($methods, $uri, $action);
        }
        $route->setContainer($this->container);

        return $route;
    }

}