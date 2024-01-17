<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', PostController::class)->middleware('auth');

Route::post('/register', [UserController::class, 'createUser']);

Route::get('/check', fn() => 'API is working properly');
Route::get('/auth', fn() => 'You are auth\'ed')->middleware('auth');
