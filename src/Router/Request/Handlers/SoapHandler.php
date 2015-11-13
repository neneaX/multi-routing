<?php
namespace MultiRouting\Router\Request\Handlers;

class SoapHandler implements Handler
{

    /**
     *
     * @var object
     */
    protected $controller;

    /**
     *
     * @param object $controller            
     */
    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    /**
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call($method, $params)
    {
        if ($method != 'login') {
            // remove the sessionId
            unset($params[0]);
        }
        
        $response = call_user_func_array([
            $this->controller,
            $method
        ], $params);
        
        return $response;
    }
}