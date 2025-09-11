<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customer;

class ApiTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Get token from header
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Remove "Bearer " if present
        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        // Find customer by token
        $customer = Customer::where('api_token', hash('sha256', $token))->first();

        if (!$customer) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Manually authenticate the customer
        auth()->shouldUse('customer');
        auth('customer')->setUser($customer);

        return $next($request);
    }
}
