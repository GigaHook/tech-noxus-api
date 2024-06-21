<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'user' => request()->user(),
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());
        
        return response()->json([
            'message' => 'Пользователь добавлен'
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $throttleKey = Str::transliterate($request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 7)) {
            return response()->json([
                'message' => 'Слишком много попыток входа, попробуйте позже',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        if (!auth()->attempt($data)) {
            RateLimiter::hit($throttleKey);

            return response()->json([
                'message' => 'Неверный логин или пароль',
            ], Response::HTTP_UNAUTHORIZED);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return response()->json([
            'user' => auth()->user(),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
