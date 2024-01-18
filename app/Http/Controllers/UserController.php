<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{
    /**
     * Админ добавляет другого админа
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request): Response
    {
        User::create($request->validated());
        
        return response([
            'message' => 'Пользователь добавлен',
        ], 201);
    }

    /**
     * Логин (только для админов)
     *
     * @param LoginRequest $request
     * @throws \Illuminate\Validation\ValidationException
     * @return Response
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->ensureIsNotRateLimited();

        $user = User::where(['login' => $data['login']])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Неверный логин или пароль',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * вышёл отсюда
     *
     * @return Response
     */
    public function logout(): Response
    {
        request()->user()->currentAccessToken()->delete();
        return response('', 204);
    }

    /**
     * Тест на перебор паролей
     * 
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    private function ensureIsNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 7)) {
            throw ValidationException::withMessages([
                'password' => 'Слишком много попыток входа, попробуйте позже',
            ]);
        }
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
