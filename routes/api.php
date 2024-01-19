<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class);

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::any('/check', fn() => 'API is working properly');
Route::any('/auth', fn() => 'You are auth\'ed')->middleware('auth:sanctum');