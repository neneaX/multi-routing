<?php
namespace MultiRouting\Adapters\JsonRpc;

use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Routing\Route;
use MultiRouting\Adapters\JsonRpc\Matching\IntentValidator;

class JsonRpcRoute extends Route
{

    /**
     * The intent (called method) that the Json RPC route responds to.
     *
     * @var string
     */
    protected $intent;

    /**
     * JsonRpcRoute constructor.
     *
     * @param array $methods
     * @param string $uri
     * @param array|\Closure $action
     */
    public function __construct($methods, $uri, $action)
    {
        $this->setValidators();

        parent::__construct($methods, $uri, $action);
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

    protected function setValidators()
    {
        static::$validators = array(
            new MethodValidator, new SchemeValidator,
            new HostValidator, new UriValidator,
            new IntentValidator
        );
    }
}