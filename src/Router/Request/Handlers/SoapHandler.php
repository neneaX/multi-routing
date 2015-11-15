<?php
namespace MultiRouting\Router\Request\Handlers;

class SoapHandler implements Handler
{
    /**
     * @var object
     */
    protected $controller;

    /**
     * @param object $controller
     */
    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, array $params = [])
    {
        $params = $params[0];

        if ($method != 'login') {
            array_shift($params); // remove the sessionId
            /** @note are the keys ordered ok? */
        }

        /** @todo add some protection (method exists) */
        $response = call_user_func_array([$this->controller, $method], $params);

        /** @note this is different than other handler responses */
        return $response;
    }
}