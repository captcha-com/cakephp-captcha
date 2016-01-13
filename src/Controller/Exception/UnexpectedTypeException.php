<?php

namespace CakeCaptcha\Controller\Exception; 

use Cake\Core\Exception\Exception;

/**
 * Unexpected type exception - used when an argument
 * cannot be found, or when user passes an invalid value.
 */
class UnexpectedTypeException extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($value, $expectedType, $code = 404)
    {
    	$message = sprintf('Expected argument of type "%s", "%s" given.', $expectedType, gettype($value));
        parent::__construct($message, $code);
    }
}
