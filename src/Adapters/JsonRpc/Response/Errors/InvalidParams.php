<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class InvalidParams extends Error
{
    protected $code = Error::INVALID_PARAMS_CODE;

    protected $message = 'Invalid method parameter(s).';

}