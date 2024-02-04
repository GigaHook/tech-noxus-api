<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'user' => request()->user(),
        ]);
    }

    /**
     * Админ добавляет другого админа
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());
        
        return response()->json([
            'message' => 'Пользователь добавлен'
        ], 201);
    }

    /**
     * Логин (только для админов)
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return response()->json([
                'message' => 'Слишком много попыток входа, попробуйте позже',
            ], 429);
        }

        if (!auth()->attempt($data)) {
            RateLimiter::hit($this->throttleKey());

            return response()->json([
                'message' => 'Неверный логин или пароль',
            ], 401);
        }

        RateLimiter::clear($this->throttleKey());

        $request->session()->regenerate();

        return response()->json([
            'user' => auth()->user(),
        ]);
    }

    /**
     * вышёл отсюда
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json();
    }

    /**
     * Ключ для рейтлимитера
     *
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::transliterate(request()->ip());
    }
}
