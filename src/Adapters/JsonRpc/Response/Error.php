<?php
namespace MultiRouting\Adapters\JsonRpc\Response;

class Error
{

    const PARSE_ERROR_CODE = -32700;

    const INVALID_REQUEST_CODE = -32600;

    const METHOD_NOT_FOUND_CODE = -32601;

    const INVALID_PARAMS_CODE = -32602;

    const INTERNAL_ERROR_CODE = -32603;

    const SERVER_ERROR_CODE = -32000;

    const GENERAL_APPLICATION_CODE = -30000;

    /**
     * A Number that indicates the error type that occurred.
     * This MUST be an integer.
     *
     * @var int
     */
    protected $code;

    /**
     * A String providing a short description of the error.
     * The message SHOULD be limited to a concise single sentence.
     *
     * @var string
     */
    protected $message;

    /**
     * A Primitive or Structured value that contains additional information about the error.
     * This may be omitted.
     * The value of this member is defined by the Server (e.g. detailed error information, nested errors etc.).
     *
     * @var mixed
     */
    protected $data;

    public function __construct($code = null, $message = null, $data = null)
    {
        $this->setCode($code);
        $this->setMessage($message);
        $this->setData($data);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    protected function setCode($code)
    {
        if (!is_null($code)) {
            $this->code = $code;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    protected function setMessage($message)
    {
        if (!is_null($message)) {
            $this->message = $message;
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    protected function setData($data)
    {
        if (!is_null($data)) {
            $this->data = $data;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }

}