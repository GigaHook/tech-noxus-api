<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class)->only(['index', 'show']);

Route::apiResource('posts', PostController::class)
    ->except(['index', 'show'])->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login'])->middleware('guest');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']); //idk
});

//TODO убрать в проде
Route::any('/check', fn() => 'API is working properly');
Route::any('/authcheck', fn() => 'You are auth\'ed')->middleware('auth:sanctum');