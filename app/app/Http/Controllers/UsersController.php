<?php

namespace App\Http\Controllers;

use App\Daos\UserDao;
use App\Errors\AppException;
use App\Http\Controllers\Cases\UsersCases;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    private UsersCases $cases;

    public function __construct() {
        $this->cases = new UsersCases(new UserDao());
    }

    public function authenticate(Request $request)
    {
        try {
            return $this->cases->authenticate($request->email, $request->password)
                ->createToken('token-api')
                ->plainTextToken;
        } catch (AppException $e) {
            return response($e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
            ]);
            return $this->cases->create($validated);
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return response('Conteúdo inválido. Revise os campos e tente novamente.', 400);
        }
    }

    public function update(Request $request, $id) {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
            ]);
            return $this->cases->update($id, $validated);
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return response('Conteúdo inválido. Revise os campos e tente novamente.', 400);
        }
    }
}
