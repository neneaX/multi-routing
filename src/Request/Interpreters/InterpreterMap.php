<?php
namespace MultiRouting\Request\Interpreters;

/**
 * Class InterpreterMap
 * @package MultiRouting\Request\Interpreters
 */
class InterpreterMap implements InterpreterMapInterface
{

    /**
     * @var InterpreterInterface[]
     */
    private $interpreters = [];

    /**
     * @param InterpreterInterface $interpreter
     */
    public function addInterpreter(InterpreterInterface $interpreter)
    {
        $this->interpreters[$interpreter->buildHash()] = $interpreter;
    }

    /**
     * @param string $hash
     *
     * @return InterpreterInterface
     */
    public function getInterpreterByHash($hash)
    {
        if (false === array_key_exists($hash, $this->interpreters)) {
            throw new InterpreterNotFound($hash, 0);
        }

        return $this->interpreters[$hash];
    }

}