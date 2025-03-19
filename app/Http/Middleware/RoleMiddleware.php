<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ✅ Import Auth facade

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        // Get the role permissions from config
        $rolePermissions = config("roles.$role", []);

        // Check if the user has the correct role
        if (!$user || $user->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
