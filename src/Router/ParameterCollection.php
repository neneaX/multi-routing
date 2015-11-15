<?php
namespace MultiRouting\Router;

class ParameterCollection implements \Iterator, \Countable
{
    /**
     * All the parameters
     *
     * @var array
     */
    protected $parameters = [];
    
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        foreach ($parameters as $name => $value) {
            $this->add(new Parameter($name, $value));
        }
    }
    
    /**
     * @param Parameter $parameter
     */
    public function add(Parameter $parameter)
    {
        $this->parameters[$parameter->getName()] = $parameter;
    }
    
    /**
     * @param string $name
     * @throws \Exception
     * @return Parameter
     */
    public function get($name)
    {
        if (!isset($this->parameters[$name])) {
            throw new \Exception('Parameter not found');
        }
        
        return $this->parameters[$name];
    }
    
    public function current()
    {}

    public function next()
    {}

    public function key()
    {}

    public function valid()
    {}

    public function rewind()
    {}

    public function count($mode = null)
    {}
    
    public function toArray() 
    {
        return array_map(function(Parameter $parameter) {
            return $parameter->getValue();
        }, $this->parameters);
    }
}
