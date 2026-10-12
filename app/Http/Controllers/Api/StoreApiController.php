<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Store::approved()->withCount(['products' => function ($q) {
            $q->published();
        }]);

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('tagline', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $stores = $query->latest()->paginate(12);

        return response()->json($stores);
    }

    public function show(string $slug): JsonResponse
    {
        $store = Store::where('status', 'approved')
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)->orWhere('id', $slug);
            })
            ->with(['user:id,name,email', 'products' => function ($q) {
                $q->published()->with('category')->take(24);
            }])
            ->first();

        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        return response()->json($store);
    }
}
