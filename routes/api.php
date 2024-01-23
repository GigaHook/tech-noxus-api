<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);

Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::any('/check', fn() => 'API is working properly');
Route::any('/authcheck', fn() => 'You are auth\'ed')->middleware('auth:sanctum');