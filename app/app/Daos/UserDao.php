<?php

namespace App\Daos;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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

    public function registerLogin(User $user): void
    {
        UserLog::create([
            'user_id'    => $user->id,
            'action'     => 'login',
            'description'=> 'Realizou login.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);

    }

    public function registerLogout(User $user): void
    {
        UserLog::create([
            'user_id'    => $user->id,
            'action'     => 'login',
            'description'=> 'Realizou logout.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);

    }
}
