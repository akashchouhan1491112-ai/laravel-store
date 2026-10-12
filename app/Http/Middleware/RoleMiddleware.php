<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        if (!in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            abort(403, 'Unauthorized. You do not have permission to access this page.');
        }

        return $next($request);
    }
}
