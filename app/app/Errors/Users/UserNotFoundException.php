<?php

namespace App\Errors\Users;

use App\Errors\AppException;
use Exception;

class UserNotFoundException extends AppException
{
    /**
     * @param array $params Parâmetros utilizados na identificação de usuário.
     */
    public function __construct(array $params, Exception $previous = null)
    {
        $msg = sprintf("Usuário não encontrado: %s", print_r($params, true));
        parent::__construct($msg, 404, $previous);
    }
}
