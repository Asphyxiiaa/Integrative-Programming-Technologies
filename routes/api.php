<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Ensure API responses are always JSON
Route::middleware('api')->group(function () {
    // Public authentication routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Protected routes with Sanctum token authentication and role-based access control
Route::middleware('auth:sanctum')->group(function () {
    // User management routes require 'edit articles' permission via Spatie
    Route::apiResource('users', UserController::class)
        ->middleware('permission:edit articles'); 

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/user', function (Request $request) {
        $user = $request->user();

        if (!$user) {
            return response()->json(['user' => null, 'auth_via' => 'none'], 200);
        }

        $authVia = 'session';
        if ($user->currentAccessToken()) {
            $authVia = 'token';
        }

        return response()->json([
            'user' => $user,
            'auth_via' => $authVia,
        ]);
    });

    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('logout-all');
});