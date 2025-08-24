<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        
        if (!$user) {
            Log::warning('Unauthenticated access attempt', [
                'route' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);
            abort(401, 'Unauthenticated');
        }

        // Check if user's role is in the allowed roles array
        if (!in_array($user->role->role_name, $roles)) {
            Log::warning('Unauthorized role access attempt', [
                'user_id' => $user->user_id,
                'attempted_roles' => $roles,
                'user_role' => $user->role->role_name,
                'route' => $request->fullUrl()
            ]);
            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }
} 