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

        if ($user && $user->hasPermission($permissionName)) {
            return $next($request);
        }

        // 3. If neither, return 403 Forbidden
        abort(403, 'This functionality is currently disabled or you do not have permission to access it.');
    }
}
