@extends('admin.layout')

@section('title', 'Vendor Payout Approvals')

@section('admin-content')
<div class="space-y-6" x-data="{ rejectModal: false, rejectUrl: '' }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Vendor Payout Approvals</h1>
            <p class="text-xs text-slate-600 mt-1">Review and process vendor balance withdrawal requests to their registered bank accounts</p>
        </div>
    </div>

    <!-- Payout Balances Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Pending Payout Requests</span>
            <div class="font-heading font-extrabold text-2xl text-amber-500">${{ number_format($pendingAmount, 2) }}</div>
            <div class="text-[11px] text-slate-600 font-medium">Awaiting administrator bank transfer</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Completed Payouts</span>
            <div class="font-heading font-extrabold text-2xl text-emerald-600">${{ number_format($completedAmount, 2) }}</div>
            <div class="text-[11px] text-slate-600 font-medium">Successfully processed to vendor accounts</div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        
        <!-- Filter -->
        <form action="{{ route('admin.payouts.index') }}" method="GET" class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Payout Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approvals</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed Transfers</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected / Reversed</option>
            </select>
        </form>

        @if($payouts->isEmpty())
            <div class="text-center py-12 text-slate-600 text-xs">
                No payout withdrawal requests found.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="pb-3">Ref #</th>
                            <th class="pb-3">Vendor Store</th>
                            <th class="pb-3">Bank Account</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($payouts as $payout)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $payout->reference_number }}</td>
                                <td class="py-3.5">
                                    <div class="font-heading font-bold text-slate-900">{{ $payout->store->name }}</div>
                                    <div class="text-[10px] text-slate-600">Owner: {{ $payout->store->user->name }}</div>
                                </td>
                                <td class="py-3.5 text-slate-700">
                                    <div class="font-semibold">{{ $payout->store->bank_name ?? 'Bank on file' }}</div>
                                    <div class="text-[10px] text-slate-600 font-mono">{{ $payout->store->account_number ?? '****' }} ({{ $payout->store->account_name ?? 'Vendor' }})</div>
                                </td>
                                <td class="py-3.5 font-heading font-extrabold text-base text-slate-900">
                                    ${{ number_format($payout->amount, 2) }}
                                </td>
                                <td class="py-3.5 text-slate-600">{{ $payout->created_at->format('M d, Y') }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $payout->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($payout->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $payout->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    @if($payout->status === 'pending')
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.payouts.approve', $payout->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-xs transition">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <button type="button" @click="rejectUrl = '{{ route('admin.payouts.reject', $payout->id) }}'; rejectModal = true"
                                                    class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] transition">
                                                Reject
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-600 italic">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payouts->links() }}
            </div>
        @endif

    </div>

    <!-- Reject Payout Modal -->
    <div x-show="rejectModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" x-transition.opacity>
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl space-y-5 border border-slate-100" @click.outside="rejectModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-heading font-extrabold text-xl text-slate-900">Reject Payout Request</h3>
                <button type="button" @click="rejectModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form :action="rejectUrl" method="POST" class="space-y-4">
                @csrf
                <p class="text-xs text-slate-600">
                    Rejecting this payout request will automatically refund and restore the balance back to the vendor's wallet balance.
                </p>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reason for Rejection *</label>
                    <textarea name="reason" rows="3" required placeholder="e.g. Invalid SWIFT code, KYC documents required, bank account name mismatch..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="button" @click="rejectModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20">
                        Confirm Rejection & Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
