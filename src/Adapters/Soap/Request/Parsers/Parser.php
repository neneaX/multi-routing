<?php
namespace MultiRouting\Adapters\Soap\Request\Parsers;

class Parser
{

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
     * Parser constructor.
     *
     * @param $requestContent
     */
    public function __construct($requestContent)
    {
        $this->setRawContent($requestContent);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
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

}