<?php

use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);
Route::post('register', [App\Http\Controllers\Api\Auth\RegisterController::class, 'store']);

Route::middleware(['auth:sanctum', 'store.throttle'])->group(function () {

    Route::post('logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
    Route::resource('users', App\Http\Controllers\Api\UserController::class);
    Route::resource('categories', App\Http\Controllers\Api\CategoryController::class);
    Route::resource('recipes', App\Http\Controllers\Api\RecipeController::class);
});
