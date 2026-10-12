<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorOrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): View
    {
        $store = Auth::user()->store;
        $query = OrderItem::where('store_id', $store->id)->with(['order.user', 'product']);

        if ($request->filled('status')) {
            $query->where('fulfillment_status', $request->status);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('product_name', 'like', "%{$term}%")
                  ->orWhereHas('order', function ($sub) use ($term) {
                      $sub->where('order_number', 'like', "%{$term}%")
                          ->orWhere('customer_name', 'like', "%{$term}%");
                  });
            });
        }

        $items = $query->latest()->paginate(12)->withQueryString();

        return view('vendor.orders.index', compact('items'));
    }

    public function show(int $id): View
    {
        $store = Auth::user()->store;
        $item = OrderItem::where('store_id', $store->id)
            ->where('id', $id)
            ->with(['order.user', 'product', 'review'])
            ->firstOrFail();

        return view('vendor.orders.show', compact('item'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $store = Auth::user()->store;
        $item = OrderItem::where('store_id', $store->id)->where('id', $id)->firstOrFail();

        $request->validate([
            'fulfillment_status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'shipping_courier' => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $this->orderService->updateItemFulfillment(
                $item,
                $request->fulfillment_status,
                $request->shipping_courier,
                $request->tracking_number
            );

            return back()->with('success', "Item fulfillment status updated to '" . ucfirst($request->fulfillment_status) . "'.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
