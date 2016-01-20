<?php
namespace MultiRouting\Adapters\Soap\Request\Handlers;

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
     */
    public function __call($method, array $params = [])
    {
        $response = call_user_func_array([$this->matchedInstance, $this->matchedMethod], $this->matchedParameters);

        /**
         * @todo check for response type returned from controller and map it accordingly
         *
         * Example:
         *  - when returning a Renderable response, make sure to render it, get the view and return it
         */

        return $response;
    }
}