<?php
namespace Examples\Basic;

use IoC\Container as IoC;
use MultiRouter\Router\Router;
use MultiRouter\Router\Request;
use MultiRouter\Router\Route;
use MultiRouter\Router\Request\Factory as RequestFactory;
use MultiRouter\Router\Response;

class Application
{
    /**
     * Inversion of Control (Dependency) Container
     * @var IoC
     */
    protected $IoC;
    
    /** @var Router */
    protected $Router;
    
    /** @var RequestFactory */
    protected $RequestFactory;
    
    public function __construct()
    {
        $this->setRouter();
        $this->setRequestFactory();
        $this->setIoC();
    }
    
    protected function setIoC()
    {
        $this->IoC = IoC::getInstance();
        $this->initIoC();
    }
    
    protected function initIoC()
    {
        $this->registerRouter();
        $this->registerHelpers();
        $this->registerMiddleware();
        $this->registerControllers();
        $this->registerServices();
    }

    protected function setRouter()
    {
        $this->Router = new Router();
    }

    protected function setRequestFactory()
    {
        $this->RequestFactory = new RequestFactory();
    }
    
    protected function registerRouter()
    {
        $router = $this->Router;
        $requestFactory = $this->RequestFactory;

        $this->IoC->singleton('Router\Router', function() use ($router) {
            return $router;
        });

        $this->IoC->singleton('Router\Request\Factory', function() use ($requestFactory) {
            return $requestFactory;
        });
        
        $this->IoC->singleton('Router\Dispatcher', function($serialization) {
            $serialization = ucfirst($serialization);
            $controller = '\MultiRouter\Router\Dispatchers\\' . $serialization . 'Dispatcher';
            return new $controller();
        });
        
        $this->IoC->singleton('Router\Request\Interpreter', function($serialization) {
            $serialization = ucfirst($serialization);
            $interpreter = '\MultiRouter\Router\Request\Interpreters\\' . $serialization . 'Interpreter';
            return new $interpreter();
        });
        
        $this->IoC->singleton('Router\Request\Handler', function($serialization, $controller) {
            $serialization = ucfirst($serialization);
            $handler = '\MultiRouter\Router\Request\Handlers\\' . $serialization . 'Handler';
            return new $handler($controller);
        });
    }
    
    protected function registerHelpers()
    {
        $this->IoC->singleton('Helpers\WsdlParser', function() {
            return new \MultiRouter\Helpers\WsdlParser();
        });
        $this->IoC->singleton('Helpers\JsonParser', function() {
            return new \MultiRouter\Helpers\JsonParser();
        });
    }
    
    protected function registerMiddleware()
    {
//         $this->IoC->singleton('Middleware\ExampleMiddleware', function() {
//             return new Examples\Basic\Middleware\ExampleMiddleware();
//         });
//         $this->IoC->singleton('Middleware\ExampleMiddleware1', function() {
//             return new Examples\Basic\Middleware\ExampleMiddleware1();
//         });
//         $this->IoC->singleton('Middleware\ExampleMiddleware2', function() {
//             return new Examples\Basic\Middleware\ExampleMiddleware2();
//         });
    }
    
    protected function registerControllers()
    {
        $this->IoC->singleton('user\AuthenticationController', function($serialization) {
            $serialization = strtolower($serialization);
            switch ($serialization) {
                case 'rpc':
                    return new \Examples\Basic\rpc\AuthController();
                    break;
                case 'soap':
                    return new \Examples\Basic\soap\AuthController();
                    break;
                default:
                    throw new \Exception('IoC Error: user\AuthenticationController - ' . $serialization);
                    break;
            }
        });
    }
    
    protected function registerServices()
    {
    }
    
    /**
     * Run the application and send the response.
     */
    public function run()
    {
        $request = $this->RequestFactory->buildRequestFromGlobals();
        $this->handle($request)->send();
    }
    
    /**
     * Handle the given request and get the response.
     *
     * @param Request $request
     * @return Response
     */
    protected function handle(Request $request)
    {
        return $this->Router->process($request);
    }
    
}