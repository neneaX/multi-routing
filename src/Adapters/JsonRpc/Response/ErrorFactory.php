<?php
namespace MultiRouting\Adapters\JsonRpc\Response;

use MultiRouting\Adapters\JsonRpc\Response\Errors\InternalError;
use MultiRouting\Adapters\JsonRpc\Response\Errors\InvalidParams;
use MultiRouting\Adapters\JsonRpc\Response\Errors\InvalidRequest;
use MultiRouting\Adapters\JsonRpc\Response\Errors\MethodNotFound;
use MultiRouting\Adapters\JsonRpc\Response\Errors\ParseError;
use MultiRouting\Adapters\JsonRpc\Response\Errors\ServerError;

class ErrorFactory
{

    /**
     * @param $data
     * @return ParseError
     */
    public static function parseError($data = null)
    {
        return new ParseError(null, null, $data);
    }

    /**
     * @param $data
     * @return InvalidRequest
     */
    public static function invalidRequest($data = null)
    {
        return new InvalidRequest(null, null, $data);
    }

    /**
     * @param $data
     * @return MethodNotFound
     */
    public static function methodNotFound($data = null)
    {
        return new MethodNotFound(null, null, $data);
    }

    /**
     * @param $data
     * @return InvalidParams
     */
    public static function invalidParams($data = null)
    {
        return new InvalidParams(null, null, $data);
    }

    /**
     * @param $data
     * @return InternalError
     */
    public static function internalError($data = null)
    {
        return new InternalError(null, null, $data);
    }

    /**
     * @param $data
     * @return ServerError
     */
    public static function serverError($data = null)
    {
        return new ServerError(null, null, $data);
    }

    /**
     * @param $code
     * @param $message
     * @param $data
     * @return Error
     */
    public static function applicationError($code, $message, $data = null)
    {
        return new Error($code, $message, $data);
    }
}