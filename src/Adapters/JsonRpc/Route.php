<?php
namespace MultiRouting\Adapters\JsonRpc;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use MultiRouting\Route as BaseRoute;
use MultiRouting\Adapters\JsonRpc\Matching\IntentValidator;
use MultiRouting\Adapters\JsonRpc\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\JsonRpc\Response\Response;
use MultiRouting\Adapters\JsonRpc\Response\ErrorFactory as ResponseErrorFactory;
use MultiRouting\Adapters\JsonRpc\Response\Content as ResponseContent;
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

        /**
         * JsonRpc HTTP request interpreter
         */
        $interpreter = new Interpreter($request);

        /**
         * JsonRpc request id
         */
        $requestId = $interpreter->getId();

        if ( ! method_exists($instance = $this->container->make($class), $method))
        {
            $error = ResponseErrorFactory::methodNotFound();
            $responseContent = ResponseContent::buildErrorContent($requestId, $error);

            return new Response($responseContent);
        }

        if ($interpreter->hasErrors()) {
            $responseContent = ResponseContent::buildErrorContent(
                $requestId,
                $interpreter->getFirstError()
            );
            return new Response($responseContent);
        }

        if (null === $requestId) {
            /**
             * The JSON-RPC request is a notification
             * Send an empty response
             *
             * @see http://www.jsonrpc.org/specification#notification
             */
            return new Response();
        }

        try {
            $proxyAlias = 'multirouting.adapters.jsonrpc.request.proxy';
            if ($this->container->bound($proxyAlias)) {
                $instance = $this->container->make(
                    $proxyAlias,
                    [
                        $instance,
                        $method,
                        $parameters
                    ]
                );
            }
        } catch (\Exception $e) {
            $error = ResponseErrorFactory::serverError();
            $responseContent = ResponseContent::buildErrorContent($requestId, $error);

            return new Response($responseContent);
        }

        try {
            $controllerResponse = call_user_func_array(
                [
                    $instance,
                    $method
                ],
                $parameters
            );
            $responseContent = ResponseContent::buildSuccessContent($requestId, $controllerResponse);
        } catch (\Exception $e) {
            $error = ResponseErrorFactory::applicationError(
                $e->getCode(),
                $e->getMessage()
            );
            $responseContent = ResponseContent::buildErrorContent($requestId, $error);
        }

        /**
         * @todo check if the response returned from the controller needs additional headers to be set on the response
         */

        return new Response($responseContent);
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
