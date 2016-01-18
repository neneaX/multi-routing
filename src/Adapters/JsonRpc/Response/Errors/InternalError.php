<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class InternalError extends Error
{
    protected $code = Error::INTERNAL_ERROR_CODE;

    protected $message = 'Internal JSON-RPC error.';

}