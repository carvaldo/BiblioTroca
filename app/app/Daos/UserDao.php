<?php

namespace App\Daos;

use App\Errors\UpdateModelException;
use App\Models\User;
use App\Models\UserLog;
use App\TypeDefs\Diff;
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
            'doer_id'    => request()->user()?->id,
            'action'     => 'create',
            'description'=> 'Conta cadastrada.',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
    }

    public function registerAccountUpdated(User $user, Diff $diff): void
    {

        UserLog::create([
            'user_id'    => $user->id,
            'doer_id'    => request()->user()->id,
            'action'     => 'update',
            'description'=> strval($diff),
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

    /**
     * @param User $user
     * @param array $fields
     * @return Diff|null Retorna os dados alterados caso haja.
     * @throws UpdateModelException Caso ocorra alguma falha ao atualizar a model.
     */
    public function update(User $user, array $fields): ?Diff
    {
        $user->fill($fields);
        $diff = $user->diff();
        if (!$diff) return null;
        if ($user->save()) return $diff;
        throw new UpdateModelException("A atualização do usuário falhou.");
    }
}
