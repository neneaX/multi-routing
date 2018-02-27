<?php
namespace MultiRouting\Request\Interpreters;

use Illuminate\Http\Request;

/**
 * Trait InterpreterTrait
 * @package MultiRouting\Request\Interpreters
 */
trait InterpreterTrait
{

    /**
     * @var InterpreterMapInterface
     */
    private $interpreterMap;

    /**
     * @return bool
     */
    private function checkInterpreterMap()
    {
        return ($this->interpreterMap instanceof InterpreterMapInterface);
    }

    /**
     *
     */
    private function setInterpreterMap()
    {
        $this->interpreterMap = new InterpreterMap();
    }

    /**
     * @return InterpreterMapInterface
     */
    private function loadInterpreterMap()
    {
        if (false === $this->checkInterpreterMap()) {
            $this->setInterpreterMap();
        }

        return $this->interpreterMap;
    }

    /**
     * @param Request $request
     *
     * @return InterpreterInterface
     */
    public function getInterpreter(Request $request)
    {
        $hash = $this->computeHash($request);

        try {
            $interpreter = $this->loadInterpreterMap()->getInterpreterByHash($hash);
        } catch (InterpreterNotFound $e) {
            $interpreter = $this->buildInterpreter($request);
            $this->loadInterpreterMap()->addInterpreter($interpreter);
        }

        return $interpreter;
    }

    /**
     * @param Request $request
     *
     * @return InterpreterInterface
     */
    public abstract function buildInterpreter(Request $request);

    /**
     * @param Request $request
     *
     * @return string
     */
    public abstract function computeHash(Request $request);

}