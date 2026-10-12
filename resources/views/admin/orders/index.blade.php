@extends('admin.layout')

@section('title', 'Global Platform Orders')

@section('admin-content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Marketplace Orders & GMV</h1>
            <p class="text-xs text-slate-600 mt-1">Multi-vendor parent orders, customer details, and individual vendor splits</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        
        <!-- Filters -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3 pb-4 border-b border-slate-100">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Order #, customer, email..." class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Order Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="payment_status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                        <th class="pb-3">Order #</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Date</th>
                        <th class="pb-3">Vendors Split</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Payment</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($orders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $order->order_number }}</td>
                            <td class="py-3.5">
                                <div class="font-bold text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-slate-600">{{ $order->customer_email }}</div>
                            </td>
                            <td class="py-3.5 text-slate-600">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 font-bold text-indigo-600">
                                {{ $order->items->map(fn($i) => $i->store->name)->unique()->implode(', ') }}
                            </td>
                            <td class="py-3.5 font-bold text-slate-900">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase
                                    {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->status_badge_class }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-bold text-[11px] transition">
                                    Audit Breakdown →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
