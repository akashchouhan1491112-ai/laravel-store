<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): View
    {
        $query = Order::with(['items.store', 'user']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.store', 'items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->orderService->cancelOrder($order, $request->reason ?? 'Cancelled by Admin');
            return back()->with('success', "Order #{$order->order_number} cancelled and inventory restored.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
