<?php
namespace MultiRouting\Adapters\JsonRpc\Response\Errors;

use MultiRouting\Adapters\JsonRpc\Response\Error;

class ParseError extends Error
{
    protected $code = Error::PARSE_ERROR_CODE;

    protected $message = 'Invalid JSON was received by the server.
        An error occurred on the server while parsing the JSON text.';

}