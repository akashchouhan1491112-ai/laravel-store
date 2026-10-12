<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::published()->with(['store', 'category', 'images']);

        // Search term
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // Category filter
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Vendor/Store filter
        if ($request->filled('store')) {
            $store = Store::where('slug', $request->store)->first();
            if ($store) {
                $query->where('store_id', $store->id);
            }
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // In-stock only
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('rating_avg'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->root()->with('children')->get();
        $stores = Store::approved()->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'stores', 'sort'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with(['store.user', 'category', 'images', 'reviews.user'])
            ->firstOrFail();

        // Increment view count
        $product->increment('views_count');

        // Related products from same category or same vendor
        $relatedProducts = Product::published()
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('store_id', $product->store_id);
            })
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
