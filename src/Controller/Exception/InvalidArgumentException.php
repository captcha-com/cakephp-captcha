<?php

namespace CakeCaptcha\Controller\Exception; 

use Cake\Core\Exception\Exception;

/**
 * Invalid Argument exception.
 */
class InvalidArgumentException extends Exception
{
    /**
     * {@inheritDoc}
     */
    public function __construct($message, $code = 404)
    {
        parent::__construct($message, $code);
    }
}
