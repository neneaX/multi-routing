<?php
namespace MultiRouting\Router\Request;

use MultiRouting\Router\Request as RouteRequest;

class Factory
{
    /**
     * @param array $query
     * @param array $body
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string $content
     * @return RouteRequest
     */
    public function buildRequest($query = [], $body = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return new RouteRequest($query, $body, $cookies, $files, $server, $content);
    }
    
    /**
     * 
     * @return RouteRequest
     */
    public function buildRequestFromGlobals()
    {
        return $this->buildRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, file_get_contents('php://input'));
    }
}
