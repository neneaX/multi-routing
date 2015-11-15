<?php
namespace MultiRouting\Router;

use IoC\Container as IoC;
use MultiRouting\Router\Matching\MethodValidator;
use MultiRouting\Router\Matching\SerializationValidator;
use MultiRouting\Router\Matching\IntentValidator;
use MultiRouting\Router\Request\Interpreters\Interpreter as RequestInterpreter;

class Route
{

    /**
     * The serialization (RPC, SOAP, REST etc.) used
     *
     * @var string
     */
    protected $serialization;

    /**
     * The HTTP method used for the request
     *
     * @var string
     */
    protected $httpMethod;

    /**
     * The method (for RPC or SOAP) or the URL (for REST) to call
     *
     * @var string
     */
    protected $intent;

    /**
     * The action (Controller@Method) used to respond to the request
     *
     * @var string
     */
    protected $action;

    /**
     * 
     * @var array
     */
    protected $matching;
    
    /**
     * The middleware used to filter the request
     *
     * @var array
     */
    protected $middleware;
    
    /**
     * The session id associated to the request
     * 
     * @var string
     */
    protected $sessionId;
    
    /**
     * The matched parameters
     * 
     * @var ParameterCollection
     */
    protected $parameters;

    public function __construct($serialization, $httpMethod, $intent, $action, $matching, $middleware = [])
    {
        $this->setSerialization($serialization);
        $this->setHttpMethod($httpMethod);
        $this->setIntent($intent);
        $this->setAction($action);
        $this->setMatching($matching);
        $this->setMiddleware($middleware);
    }

    /**
     *
     * @return string
     */
    public function getSerialization()
    {
        return $this->serialization;
    }

    protected function setSerialization($serialization)
    {
        $this->serialization = strtolower($serialization);
    }

    /**
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    protected function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    /**
     *
     * @return string
     */
    public function getIntent()
    {
        return $this->intent;
    }

    protected function setIntent($intent)
    {
        $this->intent = $intent;
    }

    /**
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    protected function setAction($action)
    {
        $this->action = $action;
    }

    /**
     *
     * @return array
     */
    public function getMatching()
    {
        return $this->matching;
    }
    
    /**
     * 
     * @param array $matching
     */
    protected function setMatching(array $matching)
    {
        $this->matching = $matching;
    }
    
    /**
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     *
     * @param array $middleware            
     */
    protected function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * 
     * @return ParameterCollection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * 
     * @param Request $request
     * @return Route
     */
    public function bind(Request $request)
    {
        $serialization = $request->getUrl()->getSerialization();
        $requestInterpreter = $this->getRequestInterpreter($serialization);
        
        $this->bindSessionId($requestInterpreter, $request);
        $this->bindParameters($requestInterpreter, $request);
        
        return $this;
    }

    protected function bindSessionId(RequestInterpreter $requestInterpreter, Request $request)
    {
        return $this->sessionId = $requestInterpreter->getSessionId($request);
    }

    /**
     * @param RequestInterpreter $requestInterpreter
     * @param Request            $request
     *
     * @return ParameterCollection
     */
    protected function bindParameters(RequestInterpreter $requestInterpreter, Request $request)
    {
        return $this->parameters = $requestInterpreter->getParameters($this, $request);
    }

    /**
     *
     * @param Request $request
     * @return boolean
     */
    public function matches(Request $request)
    {
        foreach ($this->getValidators() as $validator) {
            if (! $validator->matches($this, $request)) {
                return false;
            }
        }
        
        return true;
    }

    protected function getValidators()
    {
        return array(
            new MethodValidator(),
            new SerializationValidator(),
            new IntentValidator()
        );
    }
    
    /**
     *
     * @param string $serialization
     * @return RequestInterpreter
     */
    protected function getRequestInterpreter($serialization)
    {
        return IoC::getInstance()->resolve('Router\Request\Interpreter', [$serialization]);
    }
}