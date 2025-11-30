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

    public function create(array $fields): User
    {
        return User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
        ]);
    }
}
