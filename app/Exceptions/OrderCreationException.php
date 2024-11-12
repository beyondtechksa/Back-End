<?php

namespace App\Exceptions;

use Exception;

class OrderCreationException extends Exception
{
    public function __construct($message = "Order creation failed", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
