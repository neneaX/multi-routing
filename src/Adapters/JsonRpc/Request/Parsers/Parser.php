<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Parsers;

use MultiRouting\Adapters\JsonRpc\Request\Content;
use MultiRouting\Adapters\JsonRpc\Response\ErrorFactory;

class Parser
{

    const JSON_RPC_VERSION = '2.0';

    /**
     * The errors occurred during raw content validation against the JSON-RPC standards
     *
     * $priority => $error
     *
     * @var array
     */
    protected $errors;

    /**
     * The raw content, as received from the request
     *
     * @var \stdClass
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
     * Set the content object. Accepts json-encoded strings or objects.
     *
     * @param mixed $rawContent
     * @throws \InvalidArgumentException when content is not valid.
     */
    protected function setRawContent($rawContent)
    {
        switch (true) {
            case is_string($rawContent):
                $this->rawContent = json_decode($rawContent);
                break;

            case ($rawContent instanceof \stdClass):
                $this->rawContent = $rawContent;
                break;

            default:
                break;
        }
    }

    protected function buildContent()
    {
        $this->content = new Content(
            $this->getRawContentId(),
            $this->getRawContentMethod(),
            $this->getRawContentParams()
        );
    }

    /**
    * Get the request id from the content
    *
    * @return int|null
    */
    protected function getRawContentId()
    {
        if (isset($this->rawContent->id)) {
            return $this->rawContent->id;
        }
        return null;
    }

    /**
     * Get the called method from the content
     *
     * @return string|null
     */
    protected function getRawContentMethod()
    {
        if (isset($this->rawContent->method)) {
            return $this->rawContent->method;
        }
        return null;
    }

    /**
     * Get all the parameters from the called method (from the content).
     *
     * @return array
     */
    protected function getRawContentParams()
    {
        if (isset($this->rawContent->params)) {
            return is_array($this->rawContent->params) ? $this->rawContent->params : get_object_vars($this->rawContent->params);
        }
        return [];
    }

    /**
     * Validate the raw content and set errors accordingly
     */
    protected function validate()
    {
        try {
            $this->validateContent();
        } catch (\Exception $e) {
            $this->errors[0] = ErrorFactory::parseError();
            return;
        }

        try {
            $this->validateVersion();
        } catch (\Exception $e) {
            $this->errors[1] = ErrorFactory::invalidRequest();
        }

        try {
            $this->validateId();
        } catch (\Exception $e) {
            // The id is not set or is invalid
            try {
                $this->validateIdExists();

                // If validating passes, the id is set but invalid
                $this->errors[1] = ErrorFactory::invalidRequest();
            } catch (\Exception $e) {
                // The id is not set, the request is a Notification
            }
        }

        try {
            $this->validateMethod();
        } catch (\Exception $e) {
            $this->errors[1] = ErrorFactory::invalidRequest();
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
            || $this->rawContent->jsonrpc !== static::JSON_RPC_VERSION
        ) {
            throw new \Exception('Invalid JSON-RPC version');
        }
    }

    /**
     * Validate if the JSON-RPC id is sent in the Request
     * In case it is not sent, we assume the Request is a Notification
     *
     * @see http://www.jsonrpc.org/specification#notification
     *
     * @throws \Exception
     */
    protected function validateIdExists()
    {
        if (!property_exists($this->rawContent, 'id')) {
            throw new \Exception('The request is a notification');
        }
    }

    /**
     * Validate the JSON-RPC id sent in the Request
     *
     * @throws \Exception
     */
    protected function validateId()
    {
        if (property_exists($this->rawContent, 'id') && !is_numeric($this->rawContent->id)) {
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