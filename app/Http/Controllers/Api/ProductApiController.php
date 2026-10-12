<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::published()->with(['store:id,name,slug,logo', 'category:id,name,slug', 'images']);

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)->orWhere('id', $request->category);
            });
        }

        if ($request->filled('store')) {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('slug', $request->store)->orWhere('id', $request->store);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('rating_avg'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $perPage = min(50, (int) $request->get('per_page', 12));
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::published()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->with(['store', 'category', 'images', 'reviews.user:id,name,avatar'])
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->increment('views_count');

        return response()->json($product);
    }
}
