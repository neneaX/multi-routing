<?php
namespace MultiRouting\Adapters\JsonRpc;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use MultiRouting\Request\Interpreters\InterpreterTrait;
use MultiRouting\Request\Proxy\ProxyInterface;
use MultiRouting\Route as BaseRoute;
use MultiRouting\Adapters\JsonRpc\Matching\IntentValidator;
use MultiRouting\Adapters\JsonRpc\Request\Interpreters\Interpreter;
use MultiRouting\Adapters\JsonRpc\Response\Response;
use MultiRouting\Adapters\JsonRpc\Response\ErrorFactory as ResponseErrorFactory;
use MultiRouting\Adapters\JsonRpc\Response\Content as ResponseContent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Route
 * @package MultiRouting\Adapters\JsonRpc
 */
class Route extends BaseRoute
{

    use InterpreterTrait;

    /**
     * The intent (called method) that the Json RPC route responds to.
     *
     * @var string
     */
    protected $intent;

    /**
     * Bind the current request (in prepareRun()), to make use of the IDs and other meta properties in run()
     *
     * This is bad for your health
     * @todo forget about illuminate router and write a proper router (too many breaking changes on minor versions)
     *
     * @var Request
     */
    private $currentRequest;

    /**
     * @param Request $request
     *
     * @return Interpreter
     */
    public function buildInterpreter(Request $request)
    {
        return new Interpreter($request);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function computeHash(Request $request)
    {
        return Interpreter::computeHash($request);
    }

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
        $requestInterpreter = $this->getInterpreter($request);

        $params = $requestInterpreter->getParameters();

        return $this->parameters = $params;
    }

    /**
     * @param Request $request
     */
    public function prepareRun(Request $request)
    {
        $this->currentRequest = $request;
    }

    /**
     * Run the route action and return the response.
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function runController()
    {
        list($class, $method) = explode('@', $this->action['uses']);

        $parameters = $this->resolveClassMethodDependencies(
            $this->parameters(), $class, $method
        );

        if ($this->currentRequest instanceof Request) {
            $request = $this->currentRequest;
        } else {
            $error = ResponseErrorFactory::serverError();
            $responseContent = ResponseContent::buildErrorContent(0, $error);

            return new Response($responseContent);
        }

        /**
         * JsonRpc HTTP request interpreter
         *
         * @var Interpreter $interpreter
         */
        $interpreter = $this->getInterpreter($request);

        /**
         * JsonRpc request id
         */
        $requestId = $interpreter->getId();

        if ( ! method_exists($instance = $this->container->make($class), $method))
        {
            $error = ResponseErrorFactory::internalError();
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
                /** @var ProxyInterface $proxyInstance */
                $proxyInstance = $this->container->make($proxyAlias);

                $proxyInstance->setMatchedInstance($instance);
                $proxyInstance->setMatchedMethod($method);
                $proxyInstance->setMatchedParameters($parameters);

                $instance = $proxyInstance;
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
