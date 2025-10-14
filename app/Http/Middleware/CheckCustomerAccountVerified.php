<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CheckCustomerAccountVerified
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $customer = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or missing token'
            ], 401);
        }


        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Please login again.'
            ], 401);
        }

        if ($customer->email_verfied != 1 || $customer->account_verify != 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is not verified yet. Please verify your Account first.'
            ], 403);
        }

        return $next($request);
    }
}
