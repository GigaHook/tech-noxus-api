<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Sanctum;

class UserController extends Controller
{
    /**
     * Админ добавляет другого админа
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        User::create($request->validated());
        
        return response()
            ->json(['message' => 'Пользователь добавлен'])
            ->setStatusCode(201);
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

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 7)) {
            return response()->json([
                'errors' => ['password' => 'Слишком много попыток входа, попробуйте позже'],
            ], 429);
        }

        if (!auth()->attempt($data)) {
            RateLimiter::hit($this->throttleKey());

            return response()->json([
                'errors' => ['password' => 'Неверный логин или пароль'],
            ], 422);
        }

        $user = User::where(['login' => $data['login']])->first();

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
        //TODO неробит урод
        auth()->user()->token('api-token')->delete();
        return response()->json()->setStatusCode(204);
    }

    /**
     * Ключ для рейтлимитера
     *
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower(request()->input('login')).'|'.request()->ip());
    }
}
