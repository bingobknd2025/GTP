<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtCustomerAuth
{
    public function handle($request, Closure $next)
    {
        // Debug headers if needed
        dd($request->headers->all());

        try {
            // Header ko multiple jagah se check karo (local/server sab jagah chale)
            $authHeader = $request->header('Authorization')
                ?? $request->server('HTTP_AUTHORIZATION')
                ?? $request->server('REDIRECT_HTTP_AUTHORIZATION');

            if (!$authHeader) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            // "Bearer " remove karo agar laga ho
            if (str_starts_with($authHeader, 'Bearer ')) {
                $authHeader = substr($authHeader, 7);
            }

            // Customer guard use karo
            auth()->shouldUse('customer');

            // JWT se authenticate karo
            $user = JWTAuth::setToken($authHeader)->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        return $next($request);
    }
}
