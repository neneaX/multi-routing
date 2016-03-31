<?php
namespace MultiRouting\Request\Proxy;

interface ProxyInterface
{

    /**
     * Proxy constructor.
     * @param object $matchedInstance
     * @param string $matchedMethod
     * @param array $matchedParameters
     */
    public function __construct($matchedInstance, $matchedMethod, array $matchedParameters = []);

    /**
     * @param string $requestedMethod
     * @param array $requestedParams
     * @return mixed
     */
    public function __call($requestedMethod, array $requestedParams = []);
}