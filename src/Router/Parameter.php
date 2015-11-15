<?php

namespace MultiRouting\Router;

class Parameter
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $value;
    
    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    protected function setName($name)
    {
        $this->name = $name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    protected function setValue($value)
    {
        $this->value = $value;
    }
}
