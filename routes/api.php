<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('posts', PostController::class);

Route::post('/posts', [PostController::class, 'store']);

Route::controller(UserController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
});

Route::get('/check', fn() => 'API is working properly');
Route::get('/auth', fn() => 'You are auth\'ed')->middleware('auth');
