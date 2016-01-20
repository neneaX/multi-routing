<?php
namespace MultiRouting\Request\Interpreters;

interface InterpreterInterface
{

    /**
     * @return string
     */
    public function getIntent();

    /**
     * @return array
     */
    public function getParameters();
    
    /**
     * @return string
     */
    public function getSessionId();
    
}