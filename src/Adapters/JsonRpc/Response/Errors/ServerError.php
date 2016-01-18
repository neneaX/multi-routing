<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class ServerError extends Error
{
    protected $code = Error::SERVER_ERROR_CODE;

    protected $message = 'Internal Server error.';

}