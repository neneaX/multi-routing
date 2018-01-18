<?php

namespace MultiRouting\Request\Interpreters;

/**
 * Interface InterpreterMapInterface
 * @package MultiRouting\Request\Interpreters
 */
interface InterpreterMapInterface
{

    /**
     * @param InterpreterInterface $interpreter
     */
    public function addInterpreter(InterpreterInterface $interpreter);

    /**
     * @param string $hash
     *
     * @return InterpreterInterface
     */
    public function getInterpreterByHash($hash);

}