<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $platformGmv = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalCommissionEarned = OrderItem::sum('admin_commission');
        $totalOrdersCount = Order::count();
        $totalVendorsCount = Store::count();
        $pendingVendorsCount = Store::where('status', 'pending')->count();
        $totalProductsCount = Product::count();
        $totalCustomersCount = User::where('role', 'customer')->count();

        $pendingPayoutsCount = StoreTransaction::where('type', 'payout_withdrawal')
            ->where('status', 'pending')
            ->count();

        $recentOrders = Order::with(['items.store', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $pendingStores = Store::where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'platformGmv',
            'totalCommissionEarned',
            'totalOrdersCount',
            'totalVendorsCount',
            'pendingVendorsCount',
            'totalProductsCount',
            'totalCustomersCount',
            'pendingPayoutsCount',
            'recentOrders',
            'pendingStores'
        ));
    }
}
