<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class StoreRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = sprintf(
            'store-actions:%s',
            $request->user()?->id ?? $request->ip()
        );

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'message' => 'Muitas tentativas. Aguarde alguns segundos.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        // 10 requisições a cada 60 segundos
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
