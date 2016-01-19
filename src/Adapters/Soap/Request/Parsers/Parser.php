<?php
namespace MultiRouting\Adapters\Soap\Request\Parsers;

use MultiRouting\Adapters\Soap\Request\Content;

class Parser
{

    const JSON_RPC_VERSION = '2.0';

    /**
     * @var array
     */
    protected $errors;

    /**
     * The raw content, as received from the request
     *
     * @var \DOMDocument
     */
    protected $rawContent;

    /**
     * The content object
     *
     * @var Content
     */
    protected $content;

    /**
     * JsonRpcParser constructor.
     *
     * @param $requestContent
     */
    public function __construct($requestContent)
    {
        $this->setRawContent($requestContent);
        //$this->validate();
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
     * Set the content object. Accepts json-encoded strings or objects.
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

            case ($rawContent instanceof \DOMDocument):
                $this->rawContent = $rawContent;
                break;

            default:
                break;
        }
    }

    /**
     * @param string $rawContent
     * @return \DOMDocument
     * @throws \Exception
     */
    protected function setRawContentFromString($rawContent)
    {
        $DOM = new \DOMDocument('1.0', 'UTF-8');
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

    protected function buildContent()
    {
        if (null === $this->errors) {
            $this->content = new Content(
                $this->getRawContentId(),
                $this->getRawContentMethod(),
                $this->getRawContentParams()
            );
        }
    }

    /**
    * Get the request id from the content
    *
    * @return int
    */
    public function getRawContentId()
    {
        return $this->rawContent->id;
    }

    /**
     * Get the called method from the content
     *
     * @return string
     */
    public function getRawContentMethod()
    {
        return $this->rawContent->method;
    }

    /**
     * Get all the parameters from the called method (from the content).
     *
     * @return array
     */
    protected function getRawContentParams()
    {
        if (!isset($this->rawContent->params)) {
            return [];
        }

        return is_array($this->rawContent->params) ? $this->rawContent->params : get_object_vars($this->rawContent->params);
    }

    /**
     * Validate the raw content and set errors accordingly
     */
    protected function validate()
    {
        try {
            $this->validateContent();
        } catch (\Exception $e) {
            $this->errors['content'] = $e->getMessage();
            return;
        }

        try {
            $this->validateVersion();
        } catch (\Exception $e) {
            $this->errors['version'] = $e->getMessage();
        }

        try {
            $this->validateId();
        } catch (\Exception $e) {
            $this->errors['id'] = $e->getMessage();
        }

        try {
            $this->validateMethod();
        } catch (\Exception $e) {
            $this->errors['method'] = $e->getMessage();
        }
    }

    /**
     * Validate if the content is set.
     *
     * @throws \Exception if content not set or method is not defined for content.
     */
    protected function validateContent()
    {
        if (!($this->rawContent instanceof \stdClass)) {
            throw new \Exception('Invalid content');
        }
    }

    /**
     * Validate the JSON-RPC version sent in the Request
     *
     * @throws \Exception
     */
    protected function validateVersion()
    {
        if ( !isset($this->rawContent->jsonrpc)
            || !is_numeric($this->rawContent->jsonrpc)
            || $this->rawContent->jsonrpc != static::JSON_RPC_VERSION
        ) {
            throw new \Exception('Invalid JSON-RPC version');
        }
    }

    /**
     * Validate the JSON-RPC id sent in the Request
     *
     * @throws \Exception
     */
    protected function validateId()
    {
        if (!isset($this->rawContent->id) || !is_numeric($this->rawContent->id)) {
            throw new \Exception('Invalid JSON-RPC id');
        }
    }

    /**
     * Validate the JSON-RPC method sent in the Request
     *
     * @throws \Exception
     */
    protected function validateMethod()
    {
        if (!isset($this->rawContent->method) || !is_string($this->rawContent->method)) {
            throw new \Exception('Invalid JSON-RPC method');
        }
    }
}