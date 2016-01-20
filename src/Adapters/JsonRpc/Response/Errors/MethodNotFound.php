<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class MethodNotFound extends Error
{
    protected $code = Error::METHOD_NOT_FOUND_CODE;

    protected $message = 'The method does not exist / is not available.';

}