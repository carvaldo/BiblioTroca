<?php

namespace App\Http\Controllers\Cases;

use App\Daos\UserDao;
use App\Errors\Users\UserNotFoundException;
use App\Errors\Users\WrongPasswordException;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersCases
{
    private UserDao $dao;

    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param array $validated
     * @return ResponseFactory|Response|string
     * @throws ValidationException
     */
    public function create(array $validated) {
        $user = $this->dao->create($validated);
        $this->dao->registerAccountCreated($user);
        return $user->createToken('api-token')->plainTextToken;
    }

    /**
     * @param $id
     * @param array $validated
     * @return Response|ResponseFactory
     */
    public function update($id, array $validated) {
        $user = User::findOrFail($id);
        // TODO: Validar se o usuário possui permissão para atualizar
        if ($this->dao->update($user, $validated)) {
            $this->dao->registerAccountUpdated($user);
            return response()->setStatusCode(201, 'User updated successfully');
        }
        $this->dao->registerAccountUpdatFailed($user);
        return response()->setStatusCode(400, 'User not updated');
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserNotFoundException
     * @throws WrongPasswordException
     */
    public function authenticate(string $email, string $password): User
    {
        try {
            $user = $this->dao->findByEmail($email);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException(['email' => $email], $e);
        }
        if (!Hash::check($password, $user->password)) {
            throw new WrongPasswordException();
        }
        $this->dao->registerLogin($user);
        return $user;
    }
}
