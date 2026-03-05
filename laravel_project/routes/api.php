<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index']);
        Route::post('/', [App\Http\Controllers\UserController::class, 'store']);
        Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show']);
        Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
        Route::post('/{id}/assign-role', [App\Http\Controllers\UserController::class, 'assignRole']);
        Route::post('/{id}/remove-role', [App\Http\Controllers\UserController::class, 'removeRole']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
        Route::get('/permissions/all', [RoleController::class, 'permissions']);
    });
});