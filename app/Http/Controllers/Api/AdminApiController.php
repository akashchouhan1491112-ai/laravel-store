<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use App\Services\VendorPayoutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    protected VendorPayoutService $payoutService;

    public function __construct(VendorPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'platform_gmv' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_commission_earned' => OrderItem::sum('admin_commission'),
            'total_orders' => Order::count(),
            'total_vendors' => Store::count(),
            'pending_vendors' => Store::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_payouts' => StoreTransaction::where('type', 'payout_withdrawal')->where('status', 'pending')->count(),
        ]);
    }

    public function vendors(Request $request): JsonResponse
    {
        $query = Store::with('user')->withCount('products');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->latest()->paginate(15));
    }

    public function updateVendorStatus(Request $request, int $id): JsonResponse
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'Vendor not found.'], 404);
        }

        $request->validate([
            'status' => ['required', 'in:pending,approved,suspended,rejected'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $store->update([
            'status' => $request->status,
            'commission_rate' => $request->filled('commission_rate') ? (float) $request->commission_rate : $store->commission_rate,
        ]);

        return response()->json([
            'message' => 'Vendor status updated.',
            'store' => $store,
        ]);
    }

    public function payouts(Request $request): JsonResponse
    {
        $query = StoreTransaction::where('type', 'payout_withdrawal')->with('store.user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return response()->json($query->latest()->paginate(15));
    }

    public function processPayout(Request $request, int $id): JsonResponse
    {
        $txn = StoreTransaction::find($id);
        if (!$txn) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            if ($request->action === 'approve') {
                $this->payoutService->approvePayout($txn, $request->notes);
            } else {
                $this->payoutService->rejectPayout($txn, $request->notes ?? 'Rejected by Admin');
            }

            return response()->json([
                'message' => "Payout {$request->action}d successfully.",
                'transaction' => $txn->fresh(),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
