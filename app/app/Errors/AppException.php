<?php

namespace App\Errors;

use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct(string $message = "", int $code = -1, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
