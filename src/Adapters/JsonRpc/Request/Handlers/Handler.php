<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Handlers;

class Handler
{
    /**
     * @var object
     */
    protected $matchedInstance;

    /**
     * Handler constructor.
     *
     * @param object $matchedInstance
     */
    public function __construct($matchedInstance)
    {
        $this->matchedInstance = $matchedInstance;
    }
    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, array $params = [])
    {
        $response = call_user_func_array([$this->matchedInstance, $method], $params);

        return $response;
    }
}