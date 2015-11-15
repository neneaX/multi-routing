<?php

namespace MultiRouting\Router;

class Intent
{
    /**
     * @var string
     */
    protected $value;
    
    public function __construct($value)
    {
        $this->setValue($value);
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
