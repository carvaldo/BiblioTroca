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
        return $user->createToken('api-token')->plainTextToken;
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
        return $user;
    }
}
