<?php

namespace MultiRouting\Router\Request;

use MultiRouting\Router\Request as Request;

class Factory
{
    
    /**
     * 
     * @param array $query
     * @param array $body
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string $content
     * @return Request
     */
    public function buildRequest($query = array(), $body = array(), $cookies = array(), $files = array(), $server = array(), $content = null)
    {
        return new Request($query, $body, $cookies, $files, $server, $content);
    }
    
    /**
     * 
     * @return Request
     */
    public function buildRequestFromGlobals()
    {
        return $this->buildRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, file_get_contents('php://input'));
    }
    
}