<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Users route
Route::post('/authenticate', [UsersController::class, 'authenticate']);
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {

    });
});
