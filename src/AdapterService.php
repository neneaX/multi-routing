<?php
namespace MultiRouting;

use Illuminate\Container\Container;
use MultiRouting\Adapters\Soap\Adapter as SoapAdapter;
use MultiRouting\Adapters\JsonRpc\Adapter as JsonRpcAdapter;

class AdapterService
{
    const CONTAINER_ADAPTER_PREFIX = 'router.adapter.';

    /**
     * The router container
     *
     * @var Container
     */
    protected $container;

    /**
     * AdapterService constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * The list of registered adapters to use with the router
     *
     * Adapter $name => Adapter $class
     *
     * @var array
     */
    protected $list = [
        'Rest' => 'MultiRouting\\Adapters\\Rest\\Adapter',
        JsonRpcAdapter::name => 'MultiRouting\\Adapters\\JsonRpc\\Adapter',
        SoapAdapter::name => 'MultiRouting\\Adapters\\Soap\\Adapter'
    ];

    /**
     * The names of the allowed adapters to use with the current router configuration
     *
     * @var array
     */
    protected $allowed = [];

    /**
     * Register a new adapter in the list
     *
     * @param $name
     * @param $class
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function registerAdapter($name, $class)
    {
        $this->validateAdapterName($name);
        $this->validateAdapterClass($class);

        $this->list[$name] = $class;
    }

    /**
     * @param $name
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function allowAdapter($name)
    {
        $this->validateAdapterName($name);
        $this->validateAdapterRegistered($name);

        $this->allowed[] = $name;
    }

    /**
     * @param string $name
     * @param Router $router
     * @return mixed
     */
    public function getAdapter($name, Router $router)
    {
        $alias = static::CONTAINER_ADAPTER_PREFIX . $name;
        $this->validateAdapter($name);

        if (false === $this->container->bound($alias)) {
            $this->bindAdapter($name);
        }

        return $this->container->make($alias, [$router]);
    }

    /**
     * Bind an adapter to the container
     *
     * @param string $name
     */
    protected function bindAdapter($name)
    {
        $alias = static::CONTAINER_ADAPTER_PREFIX . $name;
        $this->container->singleton(
            $alias,
            $this->list[$name]
        );
    }

    /**
     * Validate an adapter by its name and check if available for usage
     *
     * @param $name
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function validateAdapter($name)
    {
        $this->validateAdapterName($name);
        $this->validateAdapterRegistered($name);
        $this->validateAdapterAllowed($name);
    }

    /**
     * @param $name
     * @throws \InvalidArgumentException
     */
    protected function validateAdapterName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('Invalid adapter name');
        }
    }

    /**
     * @param $class
     * @throws \InvalidArgumentException
     */
    protected function validateAdapterClass($class)
    {
        if (!is_string($class)) {
            throw new \InvalidArgumentException('Invalid adapter class');
        }
    }

    /**
     * @param $name
     * @throws \Exception
     */
    protected function validateAdapterRegistered($name)
    {
        if (!array_key_exists($name, $this->list)) {
            throw new \Exception('Adapter not registered: [' . $name . ']');
        }
    }

    /**
     * @param $name
     * @throws \Exception
     */
    protected function validateAdapterAllowed($name)
    {
        if (false === array_search($name, $this->allowed)) {
            throw new \Exception('Adapter not allowed for usage: [' . $name . ']');
        }
    }

}