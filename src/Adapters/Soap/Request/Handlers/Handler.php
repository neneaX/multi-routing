<?php
namespace MultiRouting\Adapters\Soap\Request\Handlers;

use Xqddd\Notifications\Notification;

class Handler
{
    /**
    * @var object
    */
    protected $matchedInstance;

    /**
     * @var string
     */
    protected $matchedMethod;

    /**
     * @var array
     */
    protected $matchedParameters;

    /**
     * Handler constructor.
     * @param object $matchedInstance
     * @param string $matchedMethod
     * @param array $matchedParameters
     */
    public function __construct($matchedInstance, $matchedMethod, $matchedParameters)
    {
        $this->matchedInstance = $matchedInstance;
        $this->matchedMethod = $matchedMethod;
        $this->matchedParameters = $matchedParameters;
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
                $this->matchedMethod
            ],
            $this->matchedParameters
        );

        /**
         * If the response returned from the controller is of type Notification,
         * throw an Exception to be caught by the Route
         */
        if ($response instanceof Notification) {
            throw new \Exception(
                $response->getMessage()->toString(),
                $response->getLabel()->toString()
            );
        }

        /**
         * @todo check for response type returned from controller and map it accordingly
         *
         * Example:
         *  - when returning a Renderable response, make sure to render it, get the view and return it
         */

        return $response;
    }
}