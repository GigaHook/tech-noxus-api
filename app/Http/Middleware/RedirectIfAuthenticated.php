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
                'message' => 'Доступно только неавторизованным пользователям',
            ], 422);
        }

        return $next($request);
    }
}
