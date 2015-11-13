<?php
namespace MultiRouting\Router\Exceptions;

class ResourceUrlException extends \Exception
{
    const MESSAGE_PREFIX = 'Resource URL Exception: ';
    const DEFAULT_MESSAGE = 'An unknown error has occured';

    public function __construct($message = null, $code = 400, $previous = null)
    {
        if (empty($message)) {
            $message = self::MESSAGE_PREFIX . self::DEFAULT_MESSAGE;
        } else {
            $message = self::MESSAGE_PREFIX . $message;
        }
        
        parent::__construct($message, $code, $previous);
    }
}