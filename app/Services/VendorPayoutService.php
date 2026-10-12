<?php

namespace App\Services;

use App\Models\Store;
use App\Models\StoreTransaction;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorPayoutService
{
    /**
     * Request payout withdrawal for vendor.
     */
    public function requestPayout(Store $store, float $amount, ?string $notes = null): StoreTransaction
    {
        if ($store->status !== 'approved') {
            throw new Exception('Only approved stores can request payouts.');
        }

        if ($amount < 20.00) {
            throw new Exception('Minimum payout withdrawal request is $20.00.');
        }

        if ($store->balance < $amount) {
            throw new Exception("Insufficient balance. Your available balance is \${$store->balance}.");
        }

        return DB::transaction(function () use ($store, $amount, $notes) {
            $balanceBefore = $store->balance;
            $balanceAfter = $balanceBefore - $amount;

            $store->decrement('balance', $amount);

            return StoreTransaction::create([
                'store_id' => $store->id,
                'type' => 'payout_withdrawal',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => 'pending',
                'notes' => $notes ?? "Payout withdrawal request to bank: {$store->bank_name} - Acct: {$store->account_number}",
            ]);
        });
    }

    /**
     * Admin processes / approves payout request.
     */
    public function approvePayout(StoreTransaction $transaction, ?string $adminNotes = null): void
    {
        if ($transaction->type !== 'payout_withdrawal' || $transaction->status !== 'pending') {
            throw new Exception('Invalid payout transaction.');
        }

        $transaction->update([
            'status' => 'completed',
            'notes' => trim($transaction->notes . "\nApproved by Administrator. " . ($adminNotes ?? '')),
        ]);
    }

    /**
     * Admin rejects payout request and refunds balance back to store.
     */
    public function rejectPayout(StoreTransaction $transaction, string $reason): void
    {
        if ($transaction->type !== 'payout_withdrawal' || $transaction->status !== 'pending') {
            throw new Exception('Invalid payout transaction.');
        }

        DB::transaction(function () use ($transaction, $reason) {
            $store = $transaction->store;
            $balanceBefore = $store->balance;
            $balanceAfter = $balanceBefore + $transaction->amount;

            $store->increment('balance', $transaction->amount);

            $transaction->update([
                'status' => 'rejected',
                'notes' => trim($transaction->notes . "\nRejected: " . $reason),
            ]);

            StoreTransaction::create([
                'store_id' => $store->id,
                'type' => 'earning',
                'amount' => $transaction->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => 'completed',
                'notes' => "Reversal refund for rejected payout request #{$transaction->reference_number}. Reason: {$reason}",
            ]);
        });
    }
}
