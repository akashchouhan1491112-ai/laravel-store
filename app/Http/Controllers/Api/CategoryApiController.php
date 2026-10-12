<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->root()
            ->with(['children' => function ($q) {
                $q->active()->withCount('products');
            }])
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->orderBy('sort_order')
            ->get();

        return response()->json($categories);
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->with(['children', 'products' => function ($q) {
                $q->published()->with('store')->take(20);
            }])
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return response()->json($category);
    }
}
