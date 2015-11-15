<?php

namespace MultiRouting\Router;

class RouteCollection implements \Iterator, \Countable
{
    /**
     * 
     * @var array
     */
    protected $routes = [];
    
    /**
     * 
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->routes[$route->getHttpMethod()][$route->getIntent()][$route->getSerialization()] = $route;
    }

    /**
     * @param $httpMethod
     * @param $intent
     * @param $serialization
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($httpMethod, $intent, $serialization)
    {
        if (!isset($this->routes[$httpMethod][$intent][$serialization])) {
            throw new \Exception('Route not found: ' . $httpMethod . ' ' . $intent . ' ' . $serialization);
        }
        
        return $this->routes[$httpMethod][$intent][$serialization];
    }
    
    /**
     * 
     * @param string $httpMethod
     * @throws \Exception
     * @return array
     */
    public function getRoutes($httpMethod = null)
    {
        if (is_null($httpMethod)) {
            return $this->routes;
        }
        
        if (!isset($this->routes[$httpMethod])) {
            throw new \Exception('Routes not found: ' . $httpMethod);
        }
        
        return $this->routes[$httpMethod];
    }
    
    /**
     * 
     * @param Request $request
     * @throws \Exception
     */
    public function match(Request $request)
    {
        $routes = $this->getRoutes($request->getMethod());
        
        $route = $this->check($routes, $request);
        
        if (!is_null($route)) {
            return $route->bind($request);
        }
        
        throw new \Exception('Matched route not found.');
    }
    
    /**
     * 
     * @param array $routes
     * @param Request $request
     * @return multitype:Route|null
     */
    protected function check(array $routes, Request $request)
    {
        foreach ($routes as $intent => $intentRoutes) {
            foreach ($intentRoutes as $serialization => $route) {
                if ($route->matches($request)) {
                    return $route;
                }
            }
        }
        
        return null;
    } 

    public function current () {}
    
    public function next () {}
    
    public function key () {}
    
    public function valid () {}
    
    public function rewind () {}
    
    public function count ($mode = null) {}
    
}