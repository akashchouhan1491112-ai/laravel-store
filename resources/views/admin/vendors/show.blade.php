@extends('admin.layout')

@section('title', 'Vendor ' . $store->name)

@section('admin-content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">{{ $store->name }}</h1>
            <p class="text-xs text-slate-600 mt-1">Store details, commission rate override, and merchant review</p>
        </div>
        <a href="{{ route('admin.vendors.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Vendors
        </a>
    </div>

    <!-- Vendor Approval / Status / Commission Override Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm space-y-6">
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-slate-100 shadow-sm">
                <div>
                    <h2 class="font-heading font-extrabold text-xl text-slate-900">{{ $store->name }}</h2>
                    <div class="text-xs text-indigo-600 font-semibold">{{ $store->tagline }}</div>
                    <div class="text-xs text-slate-500">Owner: {{ $store->user->name }} ({{ $store->user->email }})</div>
                </div>
            </div>

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase
                {{ $store->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($store->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                Status: {{ $store->status }}
            </span>
        </div>

        <form action="{{ route('admin.vendors.status', $store->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Approval Status *</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="approved" {{ $store->status === 'approved' ? 'selected' : '' }}>Approved (Active merchant with catalog publishing permissions)</option>
                        <option value="pending" {{ $store->status === 'pending' ? 'selected' : '' }}>Pending Review (Awaiting admin approval)</option>
                        <option value="suspended" {{ $store->status === 'suspended' ? 'selected' : '' }}>Suspended (Hidden from catalog, payouts locked)</option>
                        <option value="rejected" {{ $store->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Platform Commission Rate (%) *</label>
                    <input type="number" step="0.1" name="commission_rate" value="{{ old('commission_rate', $store->commission_rate) }}" min="0" max="100" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-indigo-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="text-[10px] text-slate-600 mt-1 block">Custom platform fee percentage applied to every order item sold by this vendor.</span>
                </div>
            </div>

            <!-- Store Banking Overview (Read-Only) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                <h4 class="font-heading font-bold text-slate-900 uppercase tracking-wider">Registered Banking Details for Payouts</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-1">
                    <div>
                        <span class="text-slate-600 block">Bank:</span>
                        <strong class="text-slate-900">{{ $store->bank_name ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-600 block">Account Name:</span>
                        <strong class="text-slate-900">{{ $store->account_name ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-600 block">Account #:</span>
                        <strong class="font-mono text-slate-900">{{ $store->account_number ?? 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-600 block">SWIFT:</span>
                        <strong class="font-mono text-slate-900">{{ $store->swift_code ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                    Save Vendor Status & Commission
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
