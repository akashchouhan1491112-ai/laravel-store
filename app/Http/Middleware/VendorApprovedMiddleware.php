<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorApprovedMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isVendor()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Only vendors can access this section.'], 403);
            }
            abort(403, 'Only registered vendors can access this section.');
        }

        $store = $user->store;

        if (!$store) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Store profile not found. Please create your store.'], 403);
            }
            return redirect()->route('vendor.store.create')->with('warning', 'Please set up your store profile first.');
        }

        if ($store->status === 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your vendor account is pending admin approval.'], 403);
            }
            return redirect()->route('vendor.dashboard')->with('info', 'Your store is currently pending administrator approval. You can manage settings while waiting.');
        }

        if ($store->status === 'suspended' || $store->status === 'rejected') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your vendor account has been suspended or rejected.'], 403);
            }
            return redirect()->route('vendor.dashboard')->with('error', 'Your vendor account has been suspended. Please contact marketplace support.');
        }

        return $next($request);
    }
}
