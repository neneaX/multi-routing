<?php
namespace MultiRouting\Router\Request\Handlers;

class RpcHandler implements Handler
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
        $responseModel = new \stdClass();
        
        try {
            $result = call_user_func_array([
                $this->controller,
                $method
            ], $params);
            
            $responseModel->result = $result;
        } catch (\Exception $e) {
            $responseModel->error = $e->getMessage();
        }
        
        $response = json_encode($responseModel);
        
        return $response;
    }
}   