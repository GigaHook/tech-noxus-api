<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)//: Response|JsonResponse
    {
        if ($request->user()) {
            return response()->json([
                'error' => 'Доступно только неавторизованным пользователям',
                'user' => $request->user(),
            ], 422);
        }

        return $next($request);
    }
}
