<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('x-api-key');

        if (!$apiKey || $apiKey !== config('app.x-api-key')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing API key'
            ], 401);
        }

        return $next($request);
    }
}
