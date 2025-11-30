<?php

namespace App\Errors\Users;

use App\Errors\AppException;
use Exception;

class WrongPasswordException extends AppException
{
    public function __construct(Exception $previous = null)
    {
        $msg = "A senha informada é inválida.";
        parent::__construct($msg, 401, $previous);
    }
}
