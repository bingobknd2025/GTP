<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ✅ API Key jo tum .env file me store karoge
        $apiKey = env('API_ACCESS_KEY', 'default_key');

        // ✅ Header se api key uthao
        $requestKey = $request->header('gtp-api-key');

        // ✅ Check
        if (!$requestKey || $requestKey !== $apiKey) {
            return response()->json([
                'error' => 'Unauthorized access. Invalid API Key.'
            ], 401);
        }

        return $next($request);
    }
}
