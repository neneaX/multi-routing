<?php
namespace MultiRouting\Adapters\Soap;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use MultiRouting\Adapters\Soap\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\Soap\Response\Response;
use MultiRouting\Request\Interpreters\InterpreterInterface;
use MultiRouting\Request\Interpreters\InterpreterMap;
use MultiRouting\Request\Interpreters\InterpreterMapInterface;
use MultiRouting\Request\Interpreters\InterpreterNotFound;
use MultiRouting\Request\Proxy\ProxyInterface;
use MultiRouting\Route as BaseRoute;
use MultiRouting\Adapters\Soap\Matching\IntentValidator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Route extends BaseRoute
{

    /**
     * The path to the server WSDL whom the request is matched against
     *
     * @var string
     */
    protected $wsdlPath;

    /**
     * The server WSDL against whom the request is matched
     *
     * @var \SimpleXMLElement
     */
    protected $wsdl;

    /**
     * The intent (called method) that the SOAP route responds to.
     *
     * @var string
     */
    protected $intent;

    /**
     * @var InterpreterMapInterface
     */
    private $interpreterMap;

    /**
     * @return bool
     */
    private function checkInterpreterMap()
    {
        return ($this->interpreterMap instanceof InterpreterMapInterface);
    }

    /**
     *
     */
    private function setInterpreterMap()
    {
        $this->interpreterMap = new InterpreterMap();
    }

    /**
     * @return InterpreterMapInterface
     */
    private function loadInterpreterMap()
    {
        if (false === $this->checkInterpreterMap()) {
            $this->setInterpreterMap();
        }

        return $this->interpreterMap;
    }

    /**
     * @param Request $request
     *
     * @return InterpreterInterface
     */
    public function getInterpreter(Request $request)
    {
        $hash = Interpreter::computeHash($request, $this->getWsdlPath());

        try {
            $interpreter = $this->loadInterpreterMap()->getInterpreterByHash($hash);
        } catch (InterpreterNotFound $e) {
            $interpreter = new Interpreter($request, $this->getWsdlPath());
            $this->loadInterpreterMap()->addInterpreter($interpreter);
        }

        return $interpreter;
    }

    /**
     * @return string
     */
    public function getWsdlPath()
    {
        return $this->wsdlPath;
    }

    /**
     * @param string $wsdlPath
     */
    public function setWsdl($wsdlPath)
    {
        $this->wsdlPath = $wsdlPath;
    }

    /**
     * @return string
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * Set the intent (called method) that the SOAP route responds to.
     *
     * @param string $intent
     * @return $this
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;

        return $this;
    }

    /**
     * Get the route validators for the instance.
     *
     * @return array
     */
    public static function getValidators()
    {
        return [
            new MethodValidator, new SchemeValidator,
            new HostValidator, new UriValidator,
            new IntentValidator
        ];
    }

    /**
     * Extract the parameter list from the request.
     *
     * @param Request $request
     * @return array
     */
    public function bindParameters(Request $request)
    {
        $requestInterpreter = $this->getInterpreter($request);

        $params = $requestInterpreter->getParameters();

        return $this->parameters = $params;
    }

    /**
     * Run the route action and return the response.
     *
     * @return mixed
     *
     * @throws NotFoundHttpException
     * @throws \SoapFault
     */
    protected function runController()
    {
        list($class, $method) = explode('@', $this->action['uses']);

        $parameters = $this->resolveClassMethodDependencies(
            $this->parameters(), $class, $method
        );

        $options = [];

        $optionsAlias = 'multirouting.adapters.soap.server.options';
        if ($this->container->bound($optionsAlias)) {
            $options = $this->container->make($optionsAlias);
        }

        try {
            $soapServer = new \SoapServer($this->getWsdlPath(), $options);
        } catch (\SoapFault $e) {
            throw new \SoapFault('500', 'The application encountered an unexpected error.');
        }

        if ( !method_exists($instance = $this->container->make($class), $method)) {
            $soapServer->fault('500', 'The application encountered an unexpected error.');
        }

        ob_start();
        try {
            $proxyAlias = 'multirouting.adapters.soap.request.proxy';
            if ($this->container->bound($proxyAlias)) {
                /** @var ProxyInterface $proxyInstance */
                $proxyInstance = $this->container->make($proxyAlias);

                $proxyInstance->setMatchedInstance($instance);
                $proxyInstance->setMatchedMethod($method);
                $proxyInstance->setMatchedParameters($parameters);

                $instance = $proxyInstance;
            }
            $soapServer->setObject($instance);
            $soapServer->handle();
            $statusCode = 200;
        } catch (\Exception $e) {
            $soapServer->fault(
                $e->getCode(),
                $e->getMessage()
            );
            $statusCode = 500;
        }
        $responseContent = ob_get_contents();
        $headers = headers_list();
        ob_end_clean();

        $responseHeaders = [];
        foreach ($headers as $header) {
            list($key, $value) = explode(':', $header, 2);
            $responseHeaders[trim($key)] = trim($value);
        }

        return new Response($responseContent, $statusCode, $responseHeaders);
    }

    /**
     * @return string
     */
    public function getCollectionIdentifier()
    {
        return $this->glueCollectionIdentifierPieces([
            Adapter::name,
            $this->domain(),
            $this->getUri(),
            $this->getIntent()
        ]);
    }
}