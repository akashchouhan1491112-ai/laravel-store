@extends('vendor.layout')

@section('title', 'Vendor Dashboard')

@section('vendor-content')
<div class="space-y-8">
    
    <!-- Top Header & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">
                {{ $store->name }} Dashboard
            </h1>
            <p class="text-xs text-slate-600 mt-1">Platform Commission Rate: <strong>{{ $store->commission_rate }}%</strong></p>
        </div>

        <div class="flex items-center gap-2">
            @if($store->isApproved())
                <a href="{{ route('vendor.products.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Product
                </a>
            @endif
        </div>
    </div>

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Gross Sales</span>
            <div class="font-heading font-extrabold text-2xl text-slate-900">${{ number_format($totalSales, 2) }}</div>
            <div class="text-[11px] text-emerald-600 font-semibold">Total revenue generated</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Net Withdrawable</span>
            <div class="font-heading font-extrabold text-2xl text-indigo-600">${{ number_format($availableBalance, 2) }}</div>
            <div class="text-[11px] text-indigo-500 font-semibold">
                <a href="{{ route('vendor.payouts.index') }}" class="hover:underline">Request Payout →</a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Pending Fulfillment</span>
            <div class="font-heading font-extrabold text-2xl text-amber-500">{{ $pendingFulfillmentCount }}</div>
            <div class="text-[11px] text-amber-600 font-semibold">
                <a href="{{ route('vendor.orders.index', ['status' => 'processing']) }}" class="hover:underline">Process shipments →</a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Active Products</span>
            <div class="font-heading font-extrabold text-2xl text-slate-900">{{ $activeProductsCount }}</div>
            <div class="text-[11px] text-slate-600 font-semibold">Live in catalog</div>
        </div>

    </div>

    <!-- Recent Vendor Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-heading font-extrabold text-lg text-slate-900">Recent Customer Items</h3>
            <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                View All Items →
            </a>
        </div>

        @if($recentOrderItems->isEmpty())
            <div class="text-center py-8 text-xs text-slate-600">
                No orders received yet. Items purchased from your store will appear here for fulfillment.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="pb-3">Order #</th>
                            <th class="pb-3">Product</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Qty</th>
                            <th class="pb-3">Net Earning</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($recentOrderItems as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $item->order->order_number }}</td>
                                <td class="py-3.5 font-bold text-slate-900 max-w-[180px] truncate">{{ $item->product_name }}</td>
                                <td class="py-3.5 text-slate-600">{{ $item->order->customer_name }}</td>
                                <td class="py-3.5 text-slate-900 font-bold">{{ $item->quantity }}</td>
                                <td class="py-3.5 font-bold text-emerald-600">${{ number_format($item->vendor_earning, 2) }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $item->fulfillment_badge_class }}">
                                        {{ $item->fulfillment_status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="{{ route('vendor.orders.show', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-bold text-[11px] transition">
                                        Fulfill / Ship
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
