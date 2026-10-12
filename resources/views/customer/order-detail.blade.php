@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Breadcrumb & Top Bar -->
    <div>
        <nav class="flex text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('customer.orders') }}" class="hover:text-indigo-600">← Back to My Orders</a>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">Order #{{ $order->order_number }}</h1>
                <p class="text-xs text-slate-600 mt-1">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
            </div>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase {{ $order->status_badge_class }}">
                Status: {{ $order->order_status }}
            </span>
        </div>
    </div>

    <!-- Items in this Order -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="font-heading font-extrabold text-xl text-slate-900 pb-4 border-b border-slate-100">
            Items in this Order
        </h3>

        <div class="space-y-6">
            @foreach($order->items as $item)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200/60 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $item->store->logo_url }}" alt="{{ $item->store->name }}" class="w-8 h-8 rounded-lg object-cover">
                            <div>
                                <span class="text-[10px] text-slate-400 font-semibold uppercase">Sold by</span>
                                <div class="font-heading font-bold text-sm text-slate-900">{{ $item->store->name }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $item->fulfillment_badge_class }}">
                                {{ $item->fulfillment_status }}
                            </span>
                        </div>
                    </div>

                    <!-- Item Details -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-xl object-cover bg-white ring-1 ring-slate-200 shrink-0">
                            <div class="min-w-0">
                                <h4 class="font-heading font-bold text-sm text-slate-900 truncate">{{ $item->product_name }}</h4>
                                <div class="text-xs text-slate-600 font-medium">Quantity: {{ $item->quantity }} • ${{ number_format($item->price, 2) }} each</div>
                                @if($item->tracking_number)
                                    <div class="text-xs text-indigo-600 font-semibold mt-1">
                                        Courier: {{ $item->shipping_courier ?? 'Standard' }} | Tracking: <span class="font-mono">{{ $item->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="font-heading font-extrabold text-lg text-slate-900">
                            ${{ number_format($item->subtotal, 2) }}
                        </div>
                    </div>

                    <!-- Leave Review / Rate Item if Delivered -->
                    @if($item->product)
                        <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                            <span class="text-slate-600">Have feedback on this item?</span>
                            <a href="{{ route('products.show', $item->product->slug) }}#reviews" class="font-bold text-indigo-600 hover:underline">
                                {{ $item->review ? '★ You Reviewed this item' : '★ Write a Product Review' }}
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Order Totals & Addresses -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
            <div class="space-y-2 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <h4 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider">Delivery Destination</h4>
                <p class="text-slate-700 leading-relaxed font-medium">
                    {{ $order->customer_name }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                    {{ $order->shipping_country }}
                </p>
                @if($order->customer_phone)
                    <p class="text-slate-600">Phone: {{ $order->customer_phone }}</p>
                @endif
            </div>

            <div class="space-y-2.5 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <h4 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider">Payment Summary</h4>
                <div class="flex justify-between text-slate-600">
                    <span>Items Subtotal:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Coupon Discount ({{ $order->coupon_code }}):</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600">
                    <span>Shipping Fee:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Tax (5%):</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-slate-200 text-sm font-bold text-slate-900">
                    <span>Total Paid:</span>
                    <span class="font-heading font-extrabold text-xl text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
