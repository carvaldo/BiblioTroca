<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Users route
Route::prefix('users')->group(function () {
    Route::post('/', [UsersController::class, 'store']); // Create user
    Route::post('/authenticate', [UsersController::class, 'authenticate']); // Authenticate user
    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::match(['patch', 'put'], '/{id}', [UsersController::class, 'update']);
    });
});
