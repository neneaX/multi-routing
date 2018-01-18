<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Interpreters;

use Illuminate\Http\Request;
use MultiRouting\Adapters\JsonRpc\Request\Parsers\Parser;
use MultiRouting\Adapters\JsonRpc\Response\Error;
use MultiRouting\Request\Interpreters\InterpreterInterface;

/**
 * Class Interpreter
 * @package MultiRouting\Adapters\JsonRpc\Request\Interpreters
 */
class Interpreter implements InterpreterInterface
{

    /**
     * @var $request
     */
    protected $request;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var string
     */
    private $hash;

    /**
     * @param Request $request
     *
     * @return string
     */
    public static function computeHash(Request $request)
    {
        return mhash(MHASH_SHA256, self::computeHashData($request));
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private static function computeHashData(Request $request)
    {
        return $request->format('json');
    }

    /**
     * RpcInterpreter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->hash = null; //reset hash
        $this->setParser();
    }

    /**
     * @return string
     */
    public function buildHash()
    {
        if (empty($this->hash)) {
            $this->hash = self::computeHash($this->request);
        }

        return $this->hash;
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

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (null !== $this->parser->getErrors());
    }

    /**
     * @return null|Error
     */
    public function getFirstError()
    {
        foreach ($this->parser->getErrors() as $error) {
            return $error;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->parser->getErrors();
    }
}