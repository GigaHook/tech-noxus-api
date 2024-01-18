<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTest
{
    public function handle(Request $request, Closure $next): Response|callable
    {
        if (!$request->user()) {
            return abort(403);
        }
        
        return $next($request);
    }
}
