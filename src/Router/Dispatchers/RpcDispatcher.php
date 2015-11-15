<?php
namespace MultiRouting\Router\Dispatchers;

use MultiRouting\Router\Route;
use MultiRouting\Router\Request;
use MultiRouting\Middleware\Middleware;
use MultiRouting\Router\Request\Handlers\Handler as RequestHandler;
use IoC\Container as IoC;

class RpcDispatcher implements Dispatcher
{
    /**
     * @param Route   $route
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function dispatch(Route $route, Request $request)
    {
        $this->runMiddleware($route, $request);
        return $this->runRoute($route, $request);
    }

    /**
     * 
     * @param string $class
     * @throws \Exception
     * @return Middleware
     */
    protected function getMiddleware($class)
    {
        try {
            $instance = IoC::getInstance()->resolve($class);
        } catch (\Exception $e) {
            throw new \Exception('Middleware Class Not Found: ' . $class);
        }
        
        return $instance;
    }
    
    /**
     * 
     * @param Route $route
     * @param Request $request
     * @throws \Exception
     */
    protected function runMiddleware(Route $route, Request $request)
    {
        $middlewareNamespace = 'Middleware\\';
        foreach ($route->getMiddleware() as $className) {
            $class = $middlewareNamespace . $className;
            
            try {
                $Middleware = $this->getMiddleware($class);
            } catch (\Exception $e) {
                throw new \Exception('Invalid Middleware: ' . $e->getMessage());
            }

            try {
                $Middleware->handle($route, $request);
            } catch (\Exception $e) {
                throw new \Exception('Request could not be handled by Middleware ' . $class . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * 
     * @throws \Exception
     * @return mixed
     */
    protected function getController($action)
    {
        list ($class, $method) = explode('@', $action);
        
        if (! method_exists($controller = IoC::getInstance()->resolve($class, ['rpc']), $method)) {
            throw new \Exception('Method Not Found');
        }
        
        return $controller;
    }
    
    /**
     *
     * @throws \Exception
     * @return string
     */
    protected function getMethod($action)
    {
        list ($class, $method) = explode('@', $action);
    
        if (! method_exists(IoC::getInstance()->resolve($class, ['rpc']), $method)) {
            throw new \Exception('Method Not Found');
        }
    
        return $method;
    }

    /**
     * 
     * @param Route $route
     * @param Request $request
     * @return string
     */
    protected function runRoute(Route $route, Request $request)
    {
        $action = $route->getAction();
        $controller = $this->getController($action);
        $method = $this->getMethod($action);
        $handler = $this->getRequestHandler($controller);

        return call_user_func_array([
            $handler,
            $method,
        ], $route->getParameters()->toArray());
    }
    
    /**
     *
     * @return RequestHandler
     */
    protected function getRequestHandler($controller)
    {
        return IoC::getInstance()->resolve('Router\Request\Handler', ['rpc', $controller]);
    }
}