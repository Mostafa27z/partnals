<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ConditionIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permissionName
     */
    public function handle(Request $request, Closure $next, string $permissionName): Response
    {
        $user = Auth::user();

        // 1. Check if user is admin (Always has access)
        if ($user && $user->role && $user->role->name === 'admin') {
            return $next($request);
        }

        // 2. Check if the user's role has this permission AND it is active
        if ($user && $user->role) {
            $hasPermission = $user->role->permissions()
                ->where('permissions.name', $permissionName)
                ->where('permissions.is_active', true)
                ->exists();

            if ($hasPermission) {
                return $next($request);
            }
        }

        // 3. If neither, return 403 Forbidden
        abort(403, 'This functionality is currently disabled or you do not have permission to access it.');
    }
}
