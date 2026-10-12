@extends('layouts.app')

@section('title', 'Order Placed - ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Celebration Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm text-center space-y-4">
        <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase">
            Order Successfully Placed
        </span>

        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight">
            Thank you, {{ $order->customer_name }}!
        </h1>

        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
            Your order <strong class="font-mono text-slate-900">{{ $order->order_number }}</strong> has been confirmed and is being prepared for shipment.
        </p>

        <div class="pt-2 flex items-center justify-center gap-4 text-xs font-semibold">
            <a href="{{ route('customer.orders.show', $order->order_number) }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-md shadow-indigo-600/20">
                Track Order Status
            </a>
            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold transition">
                Continue Shopping
            </a>
        </div>
    </div>

    <!-- Ordered Items Breakdown -->
    <div class="mt-8 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="font-heading font-extrabold text-xl text-slate-900 pb-4 border-b border-slate-100">
            Ordered Items
        </h3>

        <div class="space-y-4 divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="pt-4 first:pt-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 border border-slate-200 shrink-0">
                        <div class="min-w-0">
                            <div class="text-[11px] font-semibold text-indigo-600">Sold by {{ $item->store->name }}</div>
                            <h4 class="font-heading font-bold text-slate-900 text-sm truncate">{{ $item->product_name }}</h4>
                            <div class="text-xs text-slate-500 font-medium">Qty: {{ $item->quantity }} • ${{ number_format($item->price, 2) }} each</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $item->fulfillment_badge_class }}">
                            {{ $item->fulfillment_status }}
                        </span>
                        <div class="font-heading font-extrabold text-base text-slate-900 w-24 text-right">
                            ${{ number_format($item->subtotal, 2) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Receipt Totals -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-2">
                <div class="text-slate-600"><strong class="text-slate-900">Shipping Address:</strong></div>
                <div class="text-slate-600 leading-relaxed">{{ $order->customer_name }}<br>{{ $order->shipping_address }}<br>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</div>
                <div class="text-slate-600 pt-1">Payment Method: <strong class="text-slate-900 uppercase">{{ $order->payment_method }} ({{ $order->payment_status }})</strong></div>
            </div>

            <div class="space-y-2 sm:text-right">
                <div class="flex justify-between sm:justify-end gap-6 text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between sm:justify-end gap-6 text-emerald-600 font-bold">
                        <span>Discount ({{ $order->coupon_code }}):</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between sm:justify-end gap-6 text-slate-600">
                    <span>Shipping Fee:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-slate-600">
                    <span>Tax:</span>
                    <span class="font-semibold text-slate-900">${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-6 text-base font-bold text-slate-900 pt-2 border-t border-slate-100">
                    <span>Grand Total:</span>
                    <span class="font-heading font-extrabold text-xl text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
