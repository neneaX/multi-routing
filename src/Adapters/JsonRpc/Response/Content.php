<?php
namespace MultiRouting\Adapters\JsonRpc\Response;

use Illuminate\Contracts\Support\Jsonable;

class Content implements Jsonable
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
     * @see http://www.jsonrpc.org/specification#response_object
     *
     * @var mixed
     */
    protected $result;

    /**
     * The error returned if not successful
     *
     * This member is REQUIRED on error.
     * This member MUST NOT exist if there was no error triggered during invocation.
     * The value for this member MUST be an Object as defined in section 5.1.
     *
     * @see http://www.jsonrpc.org/specification#response_object
     * @see http://www.jsonrpc.org/specification#error_object
     *
     * @var Error
     */
    protected $error;

    /**
     * The id of the request
     *
     * This member is REQUIRED.
     * It MUST be the same as the value of the id member in the Request Object.
     * If there was an error in detecting the id in the Request object (e.g. Parse error/Invalid Request), it MUST be Null.
     *
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     * @param Error $error
     * @return Content
     */
    public static function buildError($id, Error $error)
    {
        $response = new self();
        $response->id = $id;
        $response->error = $error;

        return $response;
    }

    /**
     * @param int $id
     * @param mixed $result
     * @return Content
     */
    public static function buildResult($id, $result)
    {
        $response = new self();
        $response->id = $id;
        $response->result = $result;

        return $response;
    }

    /**
     * @return string
     */
    public function getJsonrpc()
    {
        return $this->jsonrpc;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Error $error
     */
    public function setError(Error $error)
    {
        $this->error = $error;
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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    protected function hasError()
    {
        if ($this->error instanceof Error) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $output = [
            'jsonrpc' => $this->jsonrpc,
            'id' => $this->id
        ];

        if ($this->hasError()) {
            $output['error'] = $this->error->toArray();
        } else {
            if ($this->result instanceof Jsonable) {
                $output['result'] = json_decode($this->result->toJson());
            } else {
                $output['result'] = $this->result;
            }
        }

        return $output;
    }

    public function toJson($options = 0)
    {
        if (phpversion() > '5.4.0') {
            $options |= JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES;
        }
        return json_encode($this->toArray(), $options);
    }

}