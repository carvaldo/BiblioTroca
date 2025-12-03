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
            'action'     => 'logout',
            'description'=> 'Realizou logout.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);

    }

    public function registerAccountCreated(User $user): void
    {
        UserLog::create([
            'user_id'    => $user->id,
            'doer_id'    => request()->user()->id,
            'action'     => 'create',
            'description'=> 'Conta cadastrada.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
    }

    public function registerAccountUpdated(User $user): void
    {
        UserLog::create([
            'user_id'    => $user->id,
            'doer_id'    => request()->user()->id,
            'action'     => 'update',
            'description'=> 'Conta atualizada.', // TODO: Registrar o que foi atualizado.
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
    }

    public function registerAccountUpdatFailed(User $user): void
    {
        UserLog::create([
            'user_id'    => $user->id,
            'doer_id'    => request()->user()->id,
            'action'     => 'update',
            'description'=> 'Atualização de conta falhou.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
    }

    public function update(User $user, array $fields): bool
    {
        return $user->update($fields);
    }
}
