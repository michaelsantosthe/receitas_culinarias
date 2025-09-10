<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);
Route::post('register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
    Route::resource('users', App\Http\Controllers\Api\UserController::class);

});
