<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
<<<<<<< HEAD
        // ✅ API Key jo tum .env file me store karoge
        $apiKey = env('API_ACCESS_KEY', 'default_key');

        // ✅ Header se api key uthao
        $requestKey = $request->header('gtp-api-key');

        // ✅ Check
        if (!$requestKey || $requestKey !== $apiKey) {
            return response()->json([
                'error' => 'Unauthorized access. Invalid API Key.'
=======
        $apiKey = $request->header('x-api-key');

        if (!$apiKey || $apiKey !== config('app.x-api-key')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing API key'
>>>>>>> master
            ], 401);
        }

        return $next($request);
    }
}
