<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Interpreters;

use Illuminate\Http\Request;
use MultiRouting\Adapters\JsonRpc\Request\Parsers\Parser;
use MultiRouting\Request\Interpreters\InterpreterInterface;

class Interpreter implements InterpreterInterface
{

    /**
     *
     * @var $request
     */
    protected $request;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * RpcInterpreter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->setParser();
    }

    public function setParser()
    {
        $this->parser = new Parser($this->request->getContent());
    }

    /**
     * @return string
     */
    public function getIntent()
    {
        return $this->parser->getCalledMethod();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parser->getCalledParams();
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->getSessionId();
    }
}