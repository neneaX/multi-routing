<?php
namespace MultiRouting\Adapters\Soap\Request\Interpreters;

use Illuminate\Http\Request;
use MultiRouting\Adapters\Soap\Request\Parsers\Parser;
use MultiRouting\Request\Interpreters\InterpreterInterface;
use \SimpleXMLElement as SimpleXMLElement;

/**
 * Class Interpreter
 * @package MultiRouting\Adapters\Soap\Request\Interpreters
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
     * @var \SimpleXMLElement
     */
    private $wsdl;

    /**
     * @var string
     */
    private $wsdlPath;

    /**
     * @var string
     */
    private $hash;

    /**
     * @param Request $request
     * @param string  $wsdlPath
     *
     * @return string
     */
    public static function computeHash(Request $request, $wsdlPath)
    {
        return mhash(MHASH_SHA256, self::computeHashData($request, $wsdlPath));
    }

    /**
     * @param Request $request
     * @param string  $wsdlPath
     *
     * @return string
     */
    private static function computeHashData(Request $request, $wsdlPath)
    {
        return $request->format('json') . $wsdlPath;
    }

    /**
     * Interpreter constructor.
     *
     * @param Request $request
     * @param string  $wsdlPath
     */
    public function __construct(Request $request, $wsdlPath)
    {
        $this->request = $request;
        $this->wsdlPath = $wsdlPath;
        $this->hash = null; //reset hash
        $this->setParser($this->getWsdl());
    }

    /**
     * @return \SimpleXMLElement
     */
    private function getWsdl()
    {
        if ($this->wsdl instanceof \SimpleXMLElement) {
            return $this->wsdl;
        }

        $wsdl = simplexml_load_file($this->wsdlPath);
        if (false !== $wsdl) {
            $this->wsdl = $wsdl;
        } else {
            // @todo throw exception? destroy everything? set an error? die? exit?
        }

        return $this->wsdl;
    }

    /**
     * @return string
     */
    public function buildHash()
    {
        if (empty($this->hash)) {
            $this->hash = self::computeHash($this->request, $this->wsdlPath);
        }

        return $this->hash;
    }

    /**
     * @param SimpleXMLElement $wsdl
     */
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