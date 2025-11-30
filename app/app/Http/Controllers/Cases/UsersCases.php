<?php

namespace App\Http\Controllers\Cases;

use App\Daos\UserDao;
use App\Errors\Users\UserNotFoundException;
use App\Errors\Users\WrongPasswordException;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersCases
{
    private UserDao $userDao;

    public function __construct(UserDao $dao)
    {
        $this->userDao = $dao;
    }

    /**
     * @param array $validated
     * @return ResponseFactory|Response|string
     * @throws ValidationException
     */
    public function create(array $validated) {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
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
            $user = $this->userDao->findByEmail($email);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException(['email' => $email], $e);
        }
        if (!Hash::check($password, $user->password)) {
            throw new WrongPasswordException();
        }
        return $user;
    }
}
