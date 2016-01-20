<?php
namespace MultiRouting\Adapters\JsonRpc;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Routing\Route as BaseRoute;
use MultiRouting\Adapters\JsonRpc\Exceptions\NotificationException;
use MultiRouting\Adapters\JsonRpc\Matching\IntentValidator;
use MultiRouting\Adapters\JsonRpc\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\JsonRpc\Response\Content as ResponseContent;
use MultiRouting\Adapters\JsonRpc\Response\ContentFactory as ResponseContentFactory;
use MultiRouting\Adapters\JsonRpc\Response\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Route extends BaseRoute
{

    /**
     * The intent (called method) that the Json RPC route responds to.
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
     * Set the intent (called method) that the Json RPC route responds to.
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

        /** @var ResponseContentFactory $contentFactory */
        $contentFactory = $this->container->make('MultiRouting\\Adapters\\JsonRpc\\Response\\ContentFactory');

        /**
         * JsonRpc HTTP request interpreter
         */
        $interpreter = new Interpreter($request);

        /**
         * JsonRpc request id
         */
        $requestId = $interpreter->getId();
        if ($interpreter->hasErrors()) {
            $responseContent = ResponseContent::buildError(
                $requestId,
                $interpreter->getFirstError()
            );
            return new Response($responseContent);
        }

        if (null === $requestId) {
            // The JSON-RPC request is a notification
            throw new NotificationException;
        }

        try {
            $controllerResponse = call_user_func_array([$instance, $method], $parameters);
            $responseContent = $contentFactory->buildFromResult($controllerResponse, $requestId);
            /**
             * The controller can return either a mixed value (return) or an Error (defined as \GenericApplicationImplementation\Error\ErrorInterface
             * @todo check if the responseContent is an \GenericApplicationImplementation\Error\ErrorInterface and use buildFromError instead of buildFromResult
             */
        } catch (\Exception $e) {
            $responseContent = $contentFactory->buildFromException($e, $requestId);
        }

        /**
         * @todo check if the response returned from the controller needs additional headers to be set on the response
         */

        return new Response($responseContent);
    }
}