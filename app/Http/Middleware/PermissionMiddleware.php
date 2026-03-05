<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request and check permissions, returning JSON for API requests.
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if user has ANY of the required permissions across any guard
        foreach ($permissions as $permission) {
            try {
                if ($request->user()->hasPermissionTo($permission, 'web')) {
                    return $next($request);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}

