<?php
namespace MultiRouting\Helpers;

class JsonParser
{
    /**
     * The request object
     * @var \stdClass
     */
    protected $request;
    
    /**
     * Set the request object. Accepts json-encoded strings or objects.
     *
     * @param mixed $request
     * @throws \InvalidArgumentException when request is not valid.
     */
    public function setRequest($request)
    {
        $this->request = null;

        switch (true) {
            case is_string($request):
                $this->request = json_decode($request);
                break;

            case ($request instanceof \stdClass):
                $this->request = $request;
                break;

            default:
                break;
        }

        if (!$this->request) {
            throw new \InvalidArgumentException('The input is not allowed.');
        }
    }

    /**
     * Get the called method from the request
     *
     * @return mixed
     * @throws \Exception if request not set or method is not defined for request.
     */
    public function getCalledMethod()
    {
        $this->validateRequest();
        return $this->request->method;
    }

    /**
     * Get all the parameters from the called method (from the request).
     *
     * @return array
     * @throws \Exception if request not set or method is not defined for request.
     */
    public function getCalledParams()
    {
        $this->validateRequest();

        if (!isset($this->request->params)) {
            return [];
        }

        return is_array($this->request->params) ? $this->request->params : get_object_vars($this->request->params);
    }

    /**
     * Get a specific parameter from the called method (from the request)
     * 
     * @param string $name
     * @return mixed
     * @throws \Exception if request not set or method is not defined for request.
     */
    public function getCalledParam($name)
    {
        $params = $this->getCalledParams();

        if (!array_key_exists($name, $params)) {
            return null;
        }
        
        return $params[$name];
    }

    /**
     * Validate if the request is set.
     * @throws \Exception if request not set or method is not defined for request.
     */
    protected function validateRequest()
    {
        if (!$this->request instanceof \stdClass) {
            throw new \Exception('Request not set');
        }

        if (!isset($this->request->method) || !is_string($this->request->method)) {
            throw new \Exception('No method found');
        }
    }
}