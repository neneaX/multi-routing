<?php
namespace MultiRouting\Router;

class Response
{
    
    /**
     * 
     * @var string
     */
    protected $content;
    
    /**
     * 
     * @var string
     */
    protected $statusCode;

    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($status);
    }
    
    protected function setContent($content)
    {
        $this->content = $content;
    }
    
    protected function setStatusCode($status)
    {
        $this->statusCode = $status;
    }

    public function prepare(Request $request)
    {
        return $this;
    }

    /**
     * Sends HTTP headers.
     *
     * @return Response
     */
    public function sendHeaders()
    {
        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return Response
     */
    public function sendContent()
    {
        echo $this->content;
        
        return $this;
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return Response
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }
}