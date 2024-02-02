<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if (!Auth::attempt($data)) {
            RateLimiter::hit($this->throttleKey());

            return response()->json([
                'message' => 'Неверный логин или пароль',
            ], 401);
        }

        /*$user = User::where(['login' => $data['login']])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($this->throttleKey());

            return response()->json([
                'message' => 'Неверный логин или пароль',
            ], 401);
        }*/

        $user = Auth::user();

        RateLimiter::clear($this->throttleKey());

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * вышёл отсюда
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->first()->delete();
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
