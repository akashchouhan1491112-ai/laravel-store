<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\PayoutWithdrawalRequest;
use App\Http\Requests\Vendor\ProductStoreRequest;
use App\Http\Requests\Vendor\ProductUpdateRequest;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreTransaction;
use App\Services\OrderService;
use App\Services\VendorPayoutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorApiController extends Controller
{
    protected OrderService $orderService;
    protected VendorPayoutService $payoutService;

    public function __construct(OrderService $orderService, VendorPayoutService $payoutService)
    {
        $this->orderService = $orderService;
        $this->payoutService = $payoutService;
    }

    public function dashboard(Request $request): JsonResponse
    {
        $store = $request->user()->store;

        if (!$store) {
            return response()->json(['message' => 'Store not found.'], 404);
        }

        $totalOrdersCount = OrderItem::where('store_id', $store->id)->distinct('order_id')->count('order_id');
        $pendingFulfillmentCount = OrderItem::where('store_id', $store->id)->whereIn('fulfillment_status', ['pending', 'processing'])->count();
        $activeProductsCount = Product::where('store_id', $store->id)->where('status', 'published')->count();

        return response()->json([
            'store' => $store,
            'total_sales' => $store->total_sales,
            'balance' => $store->balance,
            'total_orders' => $totalOrdersCount,
            'pending_fulfillment' => $pendingFulfillmentCount,
            'active_products' => $activeProductsCount,
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $store = $request->user()->store;
        $products = Product::where('store_id', $store->id)->with('category')->latest()->paginate(15);
        return response()->json($products);
    }

    public function storeProduct(ProductStoreRequest $request): JsonResponse
    {
        $store = $request->user()->store;
        $data = $request->validated();
        $data['store_id'] = $store->id;
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);

        if ($request->hasFile('primary_image')) {
            $data['primary_image'] = $request->file('primary_image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product,
        ], 201);
    }

    public function updateProduct(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('primary_image')) {
            $data['primary_image'] = $request->file('primary_image')->store('products', 'public');
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }

    public function deleteProduct(Request $request, Product $product): JsonResponse
    {
        if ($product->store_id !== $request->user()->store->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }

    public function orders(Request $request): JsonResponse
    {
        $store = $request->user()->store;
        $items = OrderItem::where('store_id', $store->id)->with(['order.user', 'product'])->latest()->paginate(15);
        return response()->json($items);
    }

    public function updateOrderItemStatus(Request $request, int $id): JsonResponse
    {
        $store = $request->user()->store;
        $item = OrderItem::where('store_id', $store->id)->where('id', $id)->first();

        if (!$item) {
            return response()->json(['message' => 'Order item not found.'], 404);
        }

        $request->validate([
            'fulfillment_status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'shipping_courier' => ['nullable', 'string'],
            'tracking_number' => ['nullable', 'string'],
        ]);

        try {
            $updated = $this->orderService->updateItemFulfillment(
                $item,
                $request->fulfillment_status,
                $request->shipping_courier,
                $request->tracking_number
            );

            return response()->json([
                'message' => 'Fulfillment status updated.',
                'item' => $updated,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function payouts(Request $request): JsonResponse
    {
        $store = $request->user()->store;
        $transactions = StoreTransaction::where('store_id', $store->id)->latest()->paginate(20);
        return response()->json($transactions);
    }

    public function requestPayout(PayoutWithdrawalRequest $request): JsonResponse
    {
        $store = $request->user()->store;

        try {
            $txn = $this->payoutService->requestPayout(
                $store,
                (float) $request->amount,
                $request->notes
            );

            return response()->json([
                'message' => 'Payout requested successfully.',
                'transaction' => $txn,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
