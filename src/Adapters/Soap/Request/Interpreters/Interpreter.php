<?php
namespace MultiRouting\Adapters\Soap\Request\Interpreters;

use Illuminate\Http\Request;
use MultiRouting\Adapters\Soap\Request\Parsers\Parser;
use MultiRouting\Request\Interpreters\InterpreterInterface;
use \SimpleXMLElement as SimpleXMLElement;

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
     * @param SimpleXMLElement $wsdl
     */
    public function __construct(Request $request, SimpleXMLElement $wsdl)
    {
        $this->request = $request;
        $this->setParser($wsdl);
    }

    public function setParser(SimpleXMLElement $wsdl)
    {
        $this->parser = new Parser($this->request->getContent(), $wsdl);
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
}