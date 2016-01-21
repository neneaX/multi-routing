<?php
namespace MultiRouting\Adapters\Soap\Request\Parsers;

use MultiRouting\Adapters\Soap\Request\Content;
use MultiRouting\Request\Parsers\ParserInterface;
use \SimpleXMLElement as SimpleXMLElement;
use \DOMDocument as DOMDocument;

class Parser implements ParserInterface
{

    /**
     * @var array
     */
    protected $errors;

    /**
     * The raw content, as received from the request
     *
     * @var DOMDocument
     */
    protected $rawContent;

    /**
     * The server WSDL against whom the request is matched
     *
     * @var SimpleXMLElement
     */
    protected $wsdl;

    /**
     * The content object
     *
     * @var Content
     */
    protected $content;

    /**
     * Parser constructor.
     *
     * @param mixed $requestContent
     * @param SimpleXMLElement $serverWsdl The WSDL is loaded using <code>simplexml_load_file();</code>
     */
    public function __construct($requestContent, SimpleXMLElement $serverWsdl)
    {
        $this->setWsdl($serverWsdl);
        $this->setRawContent($requestContent);
        $this->validate();
        $this->buildContent();
    }

    /**
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param SimpleXMLElement $serverWsdl
     */
    protected function setWsdl(SimpleXMLElement $serverWsdl)
    {
        $this->wsdl = $serverWsdl;
    }

    /**
     * Set the content object. Accepts xml strings or objects.
     *
     * @param mixed $rawContent
     * @throws \InvalidArgumentException when content is not valid.
     */
    protected function setRawContent($rawContent)
    {
        switch (true) {
            case is_string($rawContent):
                $this->rawContent = $this->setRawContentFromString($rawContent);
                break;

            case ($rawContent instanceof DOMDocument):
                $this->rawContent = $rawContent;
                break;

            default:
                break;
        }
    }

    protected function buildContent()
    {
        $this->content = new Content(
            $this->getRawContentMethod(),
            $this->getRawContentParams()
        );
    }

    /**
     * @param string $rawContent
     * @return DOMDocument
     * @throws \Exception
     */
    protected function setRawContentFromString($rawContent)
    {
        $DOM = new DOMDocument('1.0', 'UTF-8');
        $DOM->preserveWhiteSpace = false;
        $status = @$DOM->loadXML($rawContent);

        if (!$status) {
            // try to convert to utf-8
            if (function_exists('iconv')) {
                $rawContent = iconv('iso-8859-1', 'utf-8', $rawContent);
            }

            $status = @$DOM->loadXML($rawContent);
        }

        if (!$status) {
            throw new \Exception('String is not valid DOM.');
        }

        return $DOM;
    }

    /**
     * Get the called method from the content
     *
     * @throws \Exception
     * @return mixed
     */
    public function getRawContentMethod()
    {
        $bodyNodeList = $this->rawContent->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                $methodNameParts = explode(':', $methodNode->nodeName);
                return end($methodNameParts);
            }
        }
        return null;
    }
    /**
     * Get all the parameters from the called method (from the content)
     *
     * @return array
     * @throws \Exception
     */
    public function getRawContentParams()
    {
        $params = [];
        $bodyNodeList = $this->rawContent->getElementsByTagName('Body');
        foreach ($bodyNodeList as $bodyNode) {
            // get elements with namespaces
            $methodNode = $bodyNode->firstChild;
            if ($methodNode && $methodNode instanceof \DOMElement) {
                foreach ($methodNode->childNodes as $paramNode) {
                    if ($paramNode && $paramNode instanceof \DOMElement) {
                        $parameterNameParts = explode(':', $paramNode->nodeName);
                        $params[end($parameterNameParts)] = $paramNode->nodeValue;
                    }
                }
            }
        }
        return $params;
    }
    /**
     * Get a specific parameter from the called method (from the request)
     *
     * @param string $name
     * @return string|null
     */
    protected function getRawContentParam($name)
    {
        $params = $this->getRawContentParams();
        if (array_key_exists($name, $params)) {
            return $params[$name];
        }
        return null;
    }

    /**
     * Validate the raw content and set errors accordingly
     */
    protected function validate()
    {
        try {
            $this->validateContent();
        } catch (\Exception $e) {
            $this->errors[0] = $e->getMessage();
        }

        try {
            $this->validateMethodExists();
        } catch (\Exception $e) {
            $this->errors[1] = $e->getMessage();
        }
    }

    /**
     * Validate if the content is set.
     *
     * @throws \Exception if content not set or method is not defined for content.
     */
    protected function validateContent()
    {
        if (!($this->rawContent instanceof DOMDocument)) {
            throw new \Exception('Invalid content');
        }
    }

    /**
     * Validate if a specific method exists in the server WSDL
     *
     * @param string $calledMethodName when not set, will check the method called in the request
     *
     * @throws \Exception when method is not found.
     *
     * @return null
     */
    public function validateMethodExists($calledMethodName = '')
    {
        if (empty($calledMethodName)) {
            $calledMethodName = $this->getRawContentMethod();
        }
        // get defined methods in WSDL file
        $availableMethodNames = [];
        $availableMethodCount = count($this->wsdl->portType->operation);

        for ($i = 0; $i < $availableMethodCount; $i++) {
            $node = $this->wsdl->portType->operation[$i];
            $nodeMethodName = trim((string)$node->attributes()->name);
            $parts = explode(':', $nodeMethodName); // namespaced operation names
            $availableMethodNames[] = end($parts);
        }

        // if requested method doesn't exist then throw exception.
        if (!in_array($calledMethodName, $availableMethodNames)) {
            throw new \Exception('Method does not exists in WSDL');
        }
    }
}