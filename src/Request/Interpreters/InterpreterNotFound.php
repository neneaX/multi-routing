<?php

namespace MultiRouting\Request\Interpreters;

use \OutOfBoundsException;

/**
 * Class InterpreterNotFound
 * @package MultiRouting\Adapters\Soap\Request\Interpreters
 *
 * Occurs whenever an internal resource is requested by a given identifier from a collection or array, but it does not exist
 * Not to be confused with persistence resources "Not Found" exceptions, this should be used only on internal data structures
 *
 * e.g.:
 *  - A collection contains a list of all the registered classes to be instantiated and a non registered class is called
 *  - An internal array contains a list of codes and a non-existing one is requested
 */
class InterpreterNotFound extends OutOfBoundsException
{
    /**
     * InterpreterNotFound constructor.
     *
     * @param string          $identifier
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($identifier, $code, \Exception $previous = null)
    {
        $message = sprintf('Out of Bounds exception - the requested interpreter [%s] could not be found', $identifier);

        parent::__construct($message, $code, $previous);
    }
}