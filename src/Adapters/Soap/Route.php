<?php
namespace MultiRouting\Adapters\Soap;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Routing\Route as BaseRoute;
use MultiRouting\Adapters\Soap\Matching\IntentValidator;
use MultiRouting\Adapters\Soap\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\Soap\Response\ContentFactory;
use MultiRouting\Adapters\Soap\Response\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Route extends BaseRoute
{

    /**
     * The intent (called method) that the SOAP route responds to.
     *
     * @var string
     */
    protected $intent;

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
        $requestInterpreter = new Interpreter($request);

        $params = $requestInterpreter->getParameters();

        return $this->parameters = $params;
    }

    /**
     * Run the route action and return the response.
     *
     * @param Request $request
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function runController(Request $request)
    {
        list($class, $method) = explode('@', $this->action['uses']);

        $parameters = $this->resolveClassMethodDependencies(
            $this->parametersWithoutNulls(), $class, $method
        );

        if ( ! method_exists($instance = $this->container->make($class), $method))
        {
            throw new NotFoundHttpException;
        }

        try {
            // todo: get the wsdl path from a config file not from a constant.
            $soapServer = new \SoapServer(WSDL_PATH);
        } catch (\SoapFault $e) {
            throw new \SoapFault(500, 'The application encountered an unexpected error.');
        }

        try {
            $soapHandler = $this->container->make(
                'MultiRouting\\Adapters\\Soap\\Request\\Handlers\\Handler',
                [
                    $instance,
                    $method,
                    $parameters
                ]
            );

            $soapServer->setObject($soapHandler);
            $soapServer->handle();
        } catch (\Exception $e) {
            $soapServer->fault($e->getCode(), $e->getMessage());
        }

        exit();
    }
}