<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class InvalidRequest extends Error
{
    protected $code = Error::INVALID_REQUEST_CODE;

    protected $message = 'The JSON sent is not a valid Request object.';

}