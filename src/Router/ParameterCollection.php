<?php
namespace MultiRouting\Router;

class ParameterCollection implements \Iterator, \Countable
{

    /**
     * All the parameters
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * 
     * @param array $parameters
     */
    public function __construct(array $parameters = null)
    {
        if (!is_null($parameters)) {
            foreach ($parameters as $name => $value) {
                $this->add(new Parameter($name, $value));
            }
        }
    }
    
    /**
     *
     * @param Parameter $Parameter            
     */
    public function add(Parameter $Parameter)
    {
        $this->parameters[$Parameter->getName()] = $Parameter;
    }
    
    /**
     * 
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