<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request): View
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

        $stores = $query->latest()->paginate(9)->withQueryString();

        return view('stores.index', compact('stores'));
    }

    public function show(string $slug, Request $request): View
    {
        $store = Store::where('slug', $slug)
            ->where('status', 'approved')
            ->with('user')
            ->firstOrFail();

        $productsQuery = $store->products()->published()->with(['category', 'images']);

        if ($request->filled('category')) {
            $productsQuery->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('q')) {
            $productsQuery->search($request->q);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_low' => $productsQuery->orderBy('price', 'asc'),
            'price_high' => $productsQuery->orderBy('price', 'desc'),
            'rating' => $productsQuery->orderByDesc('rating_avg'),
            default => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate(12)->withQueryString();

        // Get categories that this vendor actually sells in
        $storeCategories = Category::whereHas('products', function ($q) use ($store) {
            $q->where('store_id', $store->id)->published();
        })->get();

        return view('stores.show', compact('store', 'products', 'storeCategories', 'sort'));
    }
}
