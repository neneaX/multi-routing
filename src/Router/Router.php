<?php
namespace MultiRouting\Router;

use IoC\Container as IoC;
use MultiRouting\Router\Dispatchers\Dispatcher;

class Router
{

    /**
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     *
     * @var Request
     */
    protected $currentRequest;

    /**
     *
     * @var Route
     */
    protected $currentRoute;

    /**
     *
     * @var string
     */
    protected static $serialization;
    const DEFAULT_SERIALIZATION = 'rpc';

    /**
     *
     * @var array
     */
    protected static $middleware;

    /**
     * Valid serialization protocols in use
     *
     * @var array
     */
    protected static $validSerializations = array(
        'rpc',
        'soap',
        'rest'
    );

    public function __construct()
    {
        $this->initRoutes();
    }

    /**
     * Initialize the Route Collection
     */
    protected function initRoutes()
    {
        $this->routes = new RouteCollection();
    }

    /**
     *
     * @param string $serialization            
     * @return Dispatcher
     */
    protected function getDispatcher($serialization)
    {
        return IoC::getInstance()->resolve('Router\Dispatcher', [$serialization]);
    }

    /**
     *
     * @param string $serialization            
     * @param string $httpMethod            
     * @param Request $request            
     * @param string $action            
     * @return Route
     */
    public function addRoute($serialization = 'rpc', $httpMethod = 'get', $intent, $action, $matching = array(), $middleware = array())
    {
        $Route = $this->createRoute($serialization, $httpMethod, $intent, $action, $matching, $middleware);
        
        $this->routes->add($Route);
        
        return $Route;
    }

    /**
     *
     * @param string $serialization            
     * @param string $httpMethod            
     * @param string $intent            
     * @param string $action            
     * @param string $middleware            
     * @return Route
     */
    protected function createRoute($serialization, $httpMethod, $intent, $action, $matching, $middleware)
    {
        $route = new Route($serialization, $httpMethod, $intent, $action, $matching, $middleware);
        
        return $route;
    }

    /**
     *
     * @param Request $request            
     * @return Route
     */
    protected function findRoute(Request $request)
    {
        return $this->currentRoute = $route = $this->routes->match($request);
    }

    /**
     *
     * @param Request $request            
     * @return Response
     */
    public function process(Request $request)
    {
        $this->currentRequest = $request;
        
        $response = $this->beforeFilter($request);
        
        if (is_null($response)) {
            $response = $this->dispatchToRoute($request);
        }
        
        $response = $this->prepareResponse($request, $response);
        
        return $response;
    }

    /**
     *
     * @param Request $request            
     * @return Response
     */
    protected function dispatchToRoute(Request $request)
    {
        $route = $this->findRoute($request);
        
        $response = $this->getDispatcher($route->getSerialization())->dispatch($route, $request);
        
        return $response;
    }

    /**
     * Create a response instance from the given value.
     *
     * @param Request $request            
     * @param mixed $response            
     * @return Response
     */
    protected function prepareResponse($request, $response)
    {
        if (! $response instanceof Response) {
            $response = new Response($response);
        }
        
        return $response->prepare($request);
    }

    /**
     *
     * @param Request $request            
     */
    protected function beforeFilter(Request $request)
    {
        $wsdl = WSDL_PATH;
        
        if (isset($request->getQuery()['wsdl'])) {
            header('Content-type: text/xml');
            return file_get_contents($wsdl);
        }
        return null;
    }

    /**
     *
     * @return string
     */
    protected static function getSerialization()
    {
        if (static::$serialization === null) {
            return static::DEFAULT_SERIALIZATION;
        }
        return static::$serialization;
    }

    /**
     *
     * @param string $serialization            
     */
    protected static function setSerialization($serialization)
    {
        if ($serialization === null) {
            static::$serialization = null;
        } elseif (in_array($serialization, static::$validSerializations)) {
            static::$serialization = $serialization;
        }
    }

    /**
     *
     * @return string
     */
    protected static function getMiddleware()
    {
        return static::$middleware;
    }

    /**
     *
     * @param string $middleware            
     */
    protected static function setMiddleware($middleware)
    {
        if ($middleware === null) {
            static::$middleware = null;
        } else {
            static::$middleware = $middleware;
        }
    }

    /**
     *
     * @param string $serialization            
     * @param Closure $callback            
     */
    public static function group($params, \Closure $callback)
    {
        if (isset($params['serialization'])) {
            $serialization = $params['serialization'];
        } else {
            $serialization = static::DEFAULT_SERIALIZATION;
        }
        
        if (isset($params['middleware'])) {
            if (! is_array($params['middleware'])) {
                $middleware = array(
                    $params['middleware']
                );
            } else {
                $middleware = $params['middleware'];
            }
        } else {
            $middleware = array();
        }
        
        static::setSerialization($serialization);
        static::setMiddleware($middleware);
        
        $callback();
        
        /**
         * Reset serialization and middleware
         */
        static::setSerialization(null);
        static::setMiddleware(null);
    }

    /**
     *
     * @param string $intent            
     * @param string $action            
     */
    public static function get($intent, $action, $matching = array())
    {
        static::map('get', $intent, $action, $matching);
    }

    /**
     *
     * @param string $intent            
     * @param string $action            
     */
    public static function post($intent, $action, $matching = array())
    {
        static::map('post', $intent, $action, $matching);
    }

    /**
     *
     * @param string $intent            
     * @param string $action            
     */
    public static function put($intent, $action, $matching = array())
    {
        static::map('put', $intent, $action, $matching);
    }

    /**
     *
     * @param string $intent            
     * @param string $action            
     */
    public static function delete($intent, $action, $matching = array())
    {
        static::map('delete', $intent, $action, $matching);
    }

    /**
     *
     * @param string $httpMethod            
     * @param string $intent            
     * @param string $action            
     */
    protected static function map($httpMethod, $intent, $action, $matching = array())
    {
        $Router = IoC::getInstance()->resolve('Router\Router');
        
        $serialization = static::getSerialization();
        $middleware = static::getMiddleware();
        
        $Router->addRoute($serialization, $httpMethod, $intent, $action, $matching, $middleware);
    }
}