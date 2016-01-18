<?php
namespace MultiRouting\Adapters\JsonRpc\Request\Parsers;

class Parser
{
    /**
     * The content object
     * @var \stdClass
     */
    protected $content;

    /**
     * JsonRpcParser constructor.
     * @param mixed $content
     */
    public function __construct($content)
    {
        $this->setContent($content);
    }

    /**
     * Set the content object. Accepts json-encoded strings or objects.
     *
     * @param mixed $content
     * @throws \InvalidArgumentException when content is not valid.
     */
    protected function setContent($content)
    {
        switch (true) {
            case is_string($content):
                $this->content = json_decode($content);
                break;

            case ($content instanceof \stdClass):
                $this->content = $content;
                break;

            default:
                break;
        }

        if (null === $this->content) {
            throw new \InvalidArgumentException('The input is not allowed.');
        }
    }

    /**
     * Get the called method from the content
     *
     * @return string
     * @throws \Exception if content not set or method is not defined for content.
     */
    public function getCalledMethod()
    {
        $this->validateContent();
        return $this->content->method;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSessionId()
    {
        $this->validateContent();
        if (!isset($this->content->sessionid)) {
            return '';
        }
        return $this->content->sessionid;
    }

    /**
     * Get all the parameters from the called method (from the content).
     *
     * @return array
     * @throws \Exception if content not set or method is not defined for content.
     */
    public function getCalledParams()
    {
        $this->validateContent();
        if (!isset($this->content->params)) {
            return [];
        }
        return is_array($this->content->params) ? $this->content->params : get_object_vars($this->content->params);
    }

    /**
     * Get a specific parameter from the called method (from the content)
     * 
     * @param string $name
     * @return mixed
     * @throws \Exception if content not set or method is not defined for content.
     */
    public function getCalledParam($name)
    {
        $params = $this->getCalledParams();
        if (!array_key_exists($name, $params)) {
            return null;
        }
        return $params[$name];
    }

    /**
     * Validate if the content is set.
     *
     * @throws \Exception if content not set or method is not defined for content.
     */
    protected function validateContent()
    {
        if (!$this->content instanceof \stdClass) {
            throw new \Exception('Content not set');
        }

        if (!isset($this->content->method) || !is_string($this->content->method)) {
            throw new \Exception('No method found');
        }
    }
}