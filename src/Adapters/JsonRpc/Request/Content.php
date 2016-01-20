<?php
namespace MultiRouting\Adapters\JsonRpc\Request;

class Content
{

    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     *
     * @var string
     */
    protected $jsonrpc = '2.0';

    /**
     * The result of the call if successful
     *
     * This member is REQUIRED on success.
     * This member MUST NOT exist if there was an error invoking the method.
     * The value of this member is determined by the method invoked on the Server.
     *
     * @see http://www.jsonrpc.org/specification#request_object
     *
     * @var string
     */
    protected $method;

    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period character (U+002E or ASCII 46)
     * are reserved for rpc-internal methods and extensions and MUST NOT be used for anything else.
     *
     * @see http://www.jsonrpc.org/specification#request_object
     *
     * @var array
     */
    protected $params;

    /**
     * The request id
     *
     * An identifier established by the Client that MUST contain a String, Number, or NULL value if included.
     * If it is not included it is assumed to be a notification.
     * The value SHOULD normally not be Null and Numbers SHOULD NOT contain fractional parts
     *
     * @see http://www.jsonrpc.org/specification#request_object
     *
     * @var int
     */
    protected $id;

    /**
     * Content constructor.
     *
     * @param $method
     * @param array $params
     * @param $id
     */
    public function __construct($id, $method, array $params = [])
    {
        $this->setId($id);
        $this->setMethod($method);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getJsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    protected function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    protected function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get a specific parameter
     *
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        if (!array_key_exists($name, $this->params)) {
            return null;
        }

        return $this->params[$name];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }

}