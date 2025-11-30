<?php

namespace App\Daos;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserDao
{
    /**
     * @param string $email
     * @return User Retorna o usuário caso encontrado.
     * @throws ModelNotFoundException
     */
    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }
}
