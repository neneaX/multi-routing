<?php
namespace MultiRouting\Router\Request\Handlers;

class RpcHandler implements Handler
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
     * @return string
     */
    public function __call($method, $params)
    {
        $responseModel = new \stdClass();

        try {
            if (!is_object($this->controller)) {
                throw new \Exception('Controller is invalid');
            }

            if (!method_exists($this->controller, $method)) {
                throw new \Exception('Method not found');
            }

            $responseModel->result = call_user_func_array([$this->controller, $method], $params);

        } catch (\Exception $e) {
            $responseModel->error = $e->getMessage();
        }

        return json_encode($responseModel);
    }
}   