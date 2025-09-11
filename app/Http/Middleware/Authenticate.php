<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if ($request->is('api/*')) {
            return null; // force JSON response for API
        }


        if (! $request->expectsJson()) {
            return route('admin.login'); // 👈 Use your custom login route
        }

        return null;
    }
}
