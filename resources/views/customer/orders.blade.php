@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">My Orders</h1>
            <p class="text-xs text-slate-500 mt-1">View order history and track recent deliveries</p>
        </div>
        <a href="{{ route('customer.profile') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
            Account Settings →
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-16 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="font-heading font-bold text-xl text-slate-900 mb-1">No orders placed yet</h3>
            <p class="text-xs text-slate-500 mb-6">Explore products from our verified stores.</p>
            <a href="{{ route('products.index') }}" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Start Shopping
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    
                    <!-- Order Header Bar -->
                    <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200/80 flex flex-wrap items-center justify-between gap-4 text-xs">
                        <div class="flex flex-wrap items-center gap-6">
                            <div>
                                <span class="text-slate-500 block">Order Number</span>
                                <span class="font-mono font-bold text-slate-900">{{ $order->order_number }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Date Placed</span>
                                <span class="font-semibold text-slate-900">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Total</span>
                                <span class="font-bold text-slate-900">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $order->status_badge_class }}">
                                {{ $order->order_status }}
                            </span>
                            <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                                Order Details →
                            </a>
                        </div>
                    </div>

                    <!-- Items in Order -->
                    <div class="p-6 divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="w-12 h-12 rounded-xl object-cover bg-slate-100 shrink-0">
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-semibold text-indigo-600">Sold by {{ $item->store->name }}</div>
                                        <div class="font-heading font-bold text-slate-900 text-sm truncate">{{ $item->product_name }}</div>
                                        <div class="text-xs text-slate-500 font-medium">Qty: {{ $item->quantity }} • ${{ number_format($item->price, 2) }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $item->fulfillment_badge_class }}">
                                        {{ $item->fulfillment_status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
