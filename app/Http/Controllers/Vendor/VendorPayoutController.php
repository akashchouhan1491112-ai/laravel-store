<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\PayoutWithdrawalRequest;
use App\Models\StoreTransaction;
use App\Services\VendorPayoutService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorPayoutController extends Controller
{
    protected VendorPayoutService $payoutService;

    public function __construct(VendorPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(): View
    {
        $store = Auth::user()->store;
        $transactions = StoreTransaction::where('store_id', $store->id)
            ->latest()
            ->paginate(15);

        $pendingPayoutsTotal = StoreTransaction::where('store_id', $store->id)
            ->where('type', 'payout_withdrawal')
            ->where('status', 'pending')
            ->sum('amount');

        $completedPayoutsTotal = StoreTransaction::where('store_id', $store->id)
            ->where('type', 'payout_withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        return view('vendor.payouts.index', compact(
            'store',
            'transactions',
            'pendingPayoutsTotal',
            'completedPayoutsTotal'
        ));
    }

    public function requestWithdrawal(PayoutWithdrawalRequest $request): RedirectResponse
    {
        $store = Auth::user()->store;

        try {
            $this->payoutService->requestPayout(
                $store,
                (float) $request->amount,
                $request->notes
            );

            return back()->with('success', "Payout withdrawal of \${$request->amount} requested successfully! Admin will process shortly.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
