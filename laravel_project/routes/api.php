<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/', [App\Http\Controllers\UserController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
});