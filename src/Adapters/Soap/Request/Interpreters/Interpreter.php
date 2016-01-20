<?php
namespace MultiRouting\Adapters\Soap\Request\Interpreters;

use Illuminate\Http\Request;
use MultiRouting\Adapters\Soap\Request\Parsers\Parser;
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
     * @return int
     */
    public function getId()
    {
        return $this->parser->getContent()->getId();
    }

    /**
     * @return string
     */
    public function getIntent()
    {
        return $this->parser->getContent()->getMethod();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parser->getContent()->getParams();
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->getSessionId();
    }
}