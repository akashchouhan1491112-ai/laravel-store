<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreTransaction;
use App\Services\VendorPayoutService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPayoutController extends Controller
{
    protected VendorPayoutService $payoutService;

    public function __construct(VendorPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(Request $request): View
    {
        $query = StoreTransaction::where('type', 'payout_withdrawal')->with('store.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payouts = $query->latest()->paginate(15)->withQueryString();

        $pendingAmount = StoreTransaction::where('type', 'payout_withdrawal')->where('status', 'pending')->sum('amount');
        $completedAmount = StoreTransaction::where('type', 'payout_withdrawal')->where('status', 'completed')->sum('amount');

        return view('admin.payouts.index', compact('payouts', 'pendingAmount', 'completedAmount'));
    }

    public function approve(Request $request, StoreTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->payoutService->approvePayout($transaction, $request->notes);
            return back()->with('success', "Payout #{$transaction->reference_number} approved successfully!");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, StoreTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->payoutService->rejectPayout($transaction, $request->reason);
            return back()->with('success', "Payout #{$transaction->reference_number} rejected and funds returned to vendor balance.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
