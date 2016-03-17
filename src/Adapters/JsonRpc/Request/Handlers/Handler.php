<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Handlers;

use MultiRouting\Adapters\JsonRpc\Exceptions\ApplicationException;
use Xqddd\Notifications\Notification;

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
     * @throws \Exception
     */
    public function __call($method, array $params = [])
    {
        $response = call_user_func_array(
            [
                $this->matchedInstance,
                $method
            ],
            $params
        );

        return $response;
    }
}