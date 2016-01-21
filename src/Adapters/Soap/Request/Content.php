<?php
namespace MultiRouting\Adapters\Soap\Request;

class Content
{

    /**
     * The method to be invoked
     *
     * @var string
     */
    protected $method;

    /**
     * The values to be used for invoking the method
     *
     * @var array
     */
    protected $params;

    /**
     * Content constructor.
     *
     * @param $method
     * @param array $params
     */
    public function __construct($method, array $params = [])
    {
        $this->setMethod($method);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    protected function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get a specific parameter
     *
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        if (!array_key_exists($name, $this->params)) {
            return null;
        }

        return $this->params[$name];
    }
}
