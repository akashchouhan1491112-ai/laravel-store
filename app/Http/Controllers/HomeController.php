<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->root()
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $featuredProducts = Product::published()
            ->with(['store', 'category'])
            ->featured()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::published()
            ->with(['store', 'category'])
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        $topStores = Store::approved()
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->orderByDesc('total_sales')
            ->take(6)
            ->get();

        $dealsProducts = Product::published()
            ->whereNotNull('compare_price')
            ->whereColumn('compare_price', '>', 'price')
            ->with(['store', 'category'])
            ->inStock()
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts',
            'topStores',
            'dealsProducts'
        ));
    }
}
