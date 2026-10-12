@extends('vendor.layout')

@section('title', 'Vendor Earnings & Payouts')

@section('vendor-content')
<div class="space-y-8" x-data="{ payoutModal: false }">

    <!-- Header & Request Payout Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Earnings & Payouts</h1>
            <p class="text-xs text-slate-600 mt-1">Track net vendor earnings, automated commission deductions, and withdrawal payouts</p>
        </div>

        @if($store->isApproved() && $store->balance >= 20.00)
            <button type="button" @click="payoutModal = true"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Request Withdrawal Payout
            </button>
        @endif
    </div>

    <!-- Payout Balance Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Available Balance</span>
            <div class="font-heading font-extrabold text-2xl text-indigo-600">${{ number_format($store->balance, 2) }}</div>
            <div class="text-[11px] text-slate-600 font-medium">Ready for withdrawal payout</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Pending Payouts</span>
            <div class="font-heading font-extrabold text-2xl text-amber-500">${{ number_format($pendingPayoutsTotal, 2) }}</div>
            <div class="text-[11px] text-slate-600 font-medium">Under admin bank review</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Completed Payouts</span>
            <div class="font-heading font-extrabold text-2xl text-emerald-600">${{ number_format($completedPayoutsTotal, 2) }}</div>
            <div class="text-[11px] text-slate-600 font-medium">Transferred to your bank</div>
        </div>
    </div>

    <!-- Transaction Ledger History -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-heading font-extrabold text-lg text-slate-900">Ledger & Transaction History</h3>
            <span class="text-xs text-slate-600">Automated double-entry audit ledger</span>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-12 text-slate-600 text-xs">
                No ledger transactions recorded yet. Earnings will appear here when orders are placed.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="pb-3">Ref #</th>
                            <th class="pb-3">Type</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Balance After</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($transactions as $txn)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $txn->reference_number }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $txn->type_badge_class }}">
                                        {{ str_replace('_', ' ', $txn->type) }}
                                    </span>
                                </td>
                                <td class="py-3.5 font-bold {{ $txn->amount >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $txn->amount >= 0 ? '+' : '' }}${{ number_format($txn->amount, 2) }}
                                </td>
                                <td class="py-3.5 font-bold text-slate-900">${{ number_format($txn->balance_after, 2) }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                                        {{ $txn->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($txn->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $txn->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-slate-600">{{ $txn->created_at->format('M d, Y - h:i A') }}</td>
                                <td class="py-3.5 text-slate-600 max-w-[200px] truncate" title="{{ $txn->notes }}">{{ $txn->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Payout Withdrawal Modal -->
    <div x-show="payoutModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-transition.opacity>
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl space-y-5 border border-slate-100" @click.outside="payoutModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-heading font-extrabold text-xl text-slate-900">Request Payout Withdrawal</h3>
                <button type="button" @click="payoutModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form action="{{ route('vendor.payouts.request') }}" method="POST" class="space-y-4">
                @csrf

                <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-900 text-xs space-y-1">
                    <div class="font-bold">Available Balance: ${{ number_format($store->balance, 2) }}</div>
                    <div class="text-[11px] text-indigo-700">Withdrawals will be transferred to your registered bank account: <strong>{{ $store->bank_name ?? 'Default Bank' }} ({{ $store->account_number ?? '****' }})</strong>.</div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Withdrawal Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" min="20.00" max="{{ $store->balance }}" value="{{ min($store->balance, 100) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-[10px] text-slate-600 mt-1 block">Minimum withdrawal: $20.00</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes / Reference (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Optional notes for marketplace accounting team..."
                              class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="button" @click="payoutModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20">
                        Confirm & Submit Payout
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
