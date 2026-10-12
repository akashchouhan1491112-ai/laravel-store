<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return view('vendor.create-store');
        }

        $totalSales = $store->total_sales;
        $availableBalance = $store->balance;

        $totalOrdersCount = OrderItem::where('store_id', $store->id)
            ->distinct('order_id')
            ->count('order_id');

        $pendingFulfillmentCount = OrderItem::where('store_id', $store->id)
            ->whereIn('fulfillment_status', ['pending', 'processing'])
            ->count();

        $activeProductsCount = Product::where('store_id', $store->id)
            ->where('status', 'published')
            ->count();

        $recentOrderItems = OrderItem::where('store_id', $store->id)
            ->with(['order', 'product'])
            ->latest()
            ->take(6)
            ->get();

        $topProducts = Product::where('store_id', $store->id)
            ->published()
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'store',
            'totalSales',
            'availableBalance',
            'totalOrdersCount',
            'pendingFulfillmentCount',
            'activeProductsCount',
            'recentOrderItems',
            'topProducts'
        ));
    }
}
