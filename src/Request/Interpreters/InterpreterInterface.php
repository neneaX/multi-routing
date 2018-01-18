<?php
namespace MultiRouting\Request\Interpreters;

/**
 * Interface InterpreterInterface
 * @package MultiRouting\Request\Interpreters
 */
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
    public function buildHash();
}