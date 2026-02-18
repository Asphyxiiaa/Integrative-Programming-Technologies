<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Public authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Protected routes that require Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::apiResource('users', UserController::class);
    
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Current authenticated user
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

    // Optional: revoke all tokens endpoint
    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('logout-all');
});