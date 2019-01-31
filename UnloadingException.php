<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class UnloadingException extends Exception
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
