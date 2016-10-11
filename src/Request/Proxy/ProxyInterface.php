<?php
namespace MultiRouting\Request\Proxy;

interface ProxyInterface
{

    /**
     * @param object $matchedInstance
     */
    public function setMatchedInstance($matchedInstance);

    /**
     * @param string $matchedMethod
     */
    public function setMatchedMethod($matchedMethod);

    /**
     * @param array $matchedParameters
     */
    public function setMatchedParameters(array $matchedParameters = []);

    /**
     * @param string $requestedMethod
     * @param array $requestedParams
     * @return mixed
     */
    public function __call($requestedMethod, array $requestedParams = []);
}