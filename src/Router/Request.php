<?php
namespace MultiRouting\Router;

class Request
{

    /**
     * Query parameters
     *
     * ($_GET)
     *
     * @var array
     */
    protected $query;

    /**
     * Request body
     *
     * ($_POST)
     *
     * @var array
     */
    protected $body;

    /**
     * Cookies
     *
     * ($_COOKIE)
     *
     * @var array
     */
    protected $cookies;

    /**
     * Uploaded files
     *
     * ($_FILES)
     *
     * @var array
     */
    protected $files;

    /**
     * Server variables and parameters
     *
     * ($_SERVER)
     *
     * @var array
     */
    protected $server;

    /**
     * Raw body content
     *
     * @var string
     */
    protected $content;
    
    /**
     * The Url object with all the information (serialization, version, uri)
     * 
     * Parsed from $_SERVER['SCRIPT_URL']
     *
     * @var Url
     */
    protected $url;

    /**
     *
     * @param array $query            
     * @param array $body            
     * @param array $cookies            
     * @param array $files            
     * @param array $server            
     * @param string $content            
     */
    public function __construct(array $query, array $body, array $cookies, array $files, array $server, $content)
    {
        $this->setQuery($query);
        $this->setBody($body);
        $this->setCookies($cookies);
        $this->setFiles($files);
        $this->setServer($server);
        $this->setContent($content);
        
        $this->setUrl();
    }

    /**
     *
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->server['REQUEST_METHOD']);
    }

    /**
     *
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    protected function setUrl()
    {
        $this->url = new Url($this->server['SCRIPT_URL']);
    }

    /**
     * 
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * 
     * @param array $query
     */
    protected function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * 
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * 
     * @param array $body
     */
    protected function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * 
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * 
     * @param array $cookies
     */
    protected function setCookies($cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * 
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * 
     * @param array $files
     */
    protected function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * 
     * @return array
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * 
     * @param array $server
     */
    protected function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 
     * @param string $content
     */
    protected function setContent($content)
    {
        $this->content = $content;
    }

}