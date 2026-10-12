@extends('vendor.layout')

@section('title', 'Vendor Order Fulfillment')

@section('vendor-content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Orders Fulfillment Center</h1>
            <p class="text-xs text-slate-600 mt-1">Manage delivery tracking and status for products ordered from your shop</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        
        <!-- Filters -->
        <form action="{{ route('vendor.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3 pb-4 border-b border-slate-100">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Order #, product, customer..." class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Fulfillment Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>

        @if($items->isEmpty())
            <div class="text-center py-12 text-slate-600 text-xs">
                No orders found matching your filter criteria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="pb-3">Order #</th>
                            <th class="pb-3">Product Item</th>
                            <th class="pb-3">Customer</th>
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Price x Qty</th>
                            <th class="pb-3">Net Earning</th>
                            <th class="pb-3">Fulfillment</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($items as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $item->order->order_number }}</td>
                                <td class="py-3.5">
                                    <div class="font-bold text-slate-900 truncate max-w-[180px]">{{ $item->product_name }}</div>
                                    @if($item->tracking_number)
                                        <div class="text-[10px] text-indigo-600 font-mono">Track: {{ $item->tracking_number }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 text-slate-600">{{ $item->order->customer_name }}</td>
                                <td class="py-3.5 text-slate-600">{{ $item->created_at->format('M d, Y') }}</td>
                                <td class="py-3.5 text-slate-900">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</td>
                                <td class="py-3.5 font-bold text-emerald-600">${{ number_format($item->vendor_earning, 2) }}</td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $item->fulfillment_badge_class }}">
                                        {{ $item->fulfillment_status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="{{ route('vendor.orders.show', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[11px] transition shadow-xs">
                                        Update Shipping →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
