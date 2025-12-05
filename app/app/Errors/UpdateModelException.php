<?php

namespace App\Errors;

use App\Errors\AppException;
use Exception;

class UpdateModelException extends AppException
{

    public function __construct(string $message, int $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
