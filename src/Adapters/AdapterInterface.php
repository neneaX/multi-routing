<?php
namespace MultiRouting\Adapters;

use MultiRouting\Router;

interface AdapterInterface
{

    /**
     * Adapter constructor.
     * @param Router $router
     */
    public function __construct(Router $router);

    /**
     * @param $methods
     * @param $uri
     * @param $action
     * @return mixed
     */
    public function buildRoute($methods, $uri, $action);
}