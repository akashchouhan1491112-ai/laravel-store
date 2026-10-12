@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">Shopping Cart</h1>
        <p class="text-xs text-slate-500 mt-1">Review your items and proceed to secure checkout</p>
    </div>

    @if(empty($groupedItems))
        <div class="bg-white rounded-3xl border border-slate-200 p-16 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h2 class="font-heading font-bold text-2xl text-slate-900 mb-2">Your cart is empty</h2>
            <p class="text-xs text-slate-500 mb-8 max-w-sm mx-auto leading-relaxed">
                Discover exceptional artisan goods, electronics, and lifestyle apparel from our verified stores.
            </p>
            <a href="{{ route('products.index') }}" class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                Start Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Cart Items Grouped by Store (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                @foreach($groupedItems as $group)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                        
                        <!-- Store Header Pill -->
                        <div class="bg-slate-50/80 px-6 py-3.5 border-b border-slate-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $group['store']->logo_url }}" alt="{{ $group['store']->name }}" class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-300">
                                <div>
                                    <span class="text-xs text-slate-500 font-medium">Sold by</span>
                                    <a href="{{ route('stores.show', $group['store']->slug) }}" class="font-heading font-bold text-sm text-slate-900 hover:text-indigo-600 transition ml-1">
                                        {{ $group['store']->name }}
                                    </a>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-indigo-600">
                                Subtotal: ${{ number_format($group['store_subtotal'], 2) }}
                            </span>
                        </div>

                        <!-- Vendor's items list in cart -->
                        <div class="p-6 divide-y divide-slate-100">
                            @foreach($group['items'] as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    
                                    <div class="flex items-center gap-4 min-w-0">
                                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 border border-slate-200 shrink-0">
                                        <div class="min-w-0">
                                            <div class="text-[10px] font-bold text-indigo-600 uppercase">{{ $item->product->category->name }}</div>
                                            <h4 class="font-heading font-bold text-sm text-slate-900 hover:text-indigo-600 transition truncate">
                                                <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                            </h4>
                                            <div class="text-xs text-slate-600 font-medium mt-0.5">${{ number_format($item->product->price, 2) }} each</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                                        <!-- Quantity Form -->
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                                            @csrf
                                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="px-3 py-1 text-slate-600 hover:bg-slate-200 transition font-bold text-xs">-</button>
                                            <span class="px-3 text-xs font-bold text-slate-900">{{ $item->quantity }}</span>
                                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-3 py-1 text-slate-600 hover:bg-slate-200 transition font-bold text-xs">+</button>
                                        </form>

                                        <!-- Item Subtotal -->
                                        <div class="font-heading font-extrabold text-base text-slate-900 w-20 text-right">
                                            ${{ number_format($item->subtotal, 2) }}
                                        </div>

                                        <!-- Remove button -->
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Remove item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach

                <!-- Cart Actions & Clear -->
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1">
                        ← Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:underline">
                            Clear Entire Cart
                        </button>
                    </form>
                </div>

            </div>

            <!-- Order Summary & Checkout Card (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-28">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                    <h3 class="font-heading font-extrabold text-xl text-slate-900 pb-4 border-b border-slate-100">Order Summary</h3>

                    <!-- Pricing Breakdown -->
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal ({{ $summary['count'] }} items)</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['subtotal'], 2) }}</span>
                        </div>

                        @if($summary['discount'] > 0)
                            <div class="flex justify-between text-emerald-600 font-semibold bg-emerald-50 p-2.5 rounded-xl border border-emerald-200">
                                <span>Coupon ({{ $summary['coupon_code'] }})</span>
                                <span>-${{ number_format($summary['discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Shipping</span>
                            @if($summary['shipping_fee'] == 0)
                                <span class="font-bold text-emerald-600 uppercase">FREE</span>
                            @else
                                <span class="font-bold text-slate-900">${{ number_format($summary['shipping_fee'], 2) }}</span>
                            @endif
                        </div>

                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Tax (5%)</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['tax_amount'], 2) }}</span>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="font-heading font-extrabold text-base text-slate-900">Total</span>
                            <span class="font-heading font-extrabold text-2xl text-indigo-600">${{ number_format($summary['total'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Promo Code Form -->
                    <div class="pt-2 border-t border-slate-100">
                        @if(!$summary['coupon_code'])
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" placeholder="Promo code (e.g. WELCOME10)" required
                                       class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium uppercase focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm">
                                    Apply
                                </button>
                            </form>
                            <div class="text-[10px] text-slate-500 mt-1.5">Have a promo code? Enter code <strong class="text-indigo-600 font-semibold">WELCOME10</strong> for 10% off.</div>
                        @else
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="flex items-center justify-between text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                                @csrf
                                <span class="text-slate-700 font-medium">Coupon <strong>{{ $summary['coupon_code'] }}</strong> applied</span>
                                <button type="submit" class="text-rose-600 font-bold hover:underline">Remove</button>
                            </form>
                        @endif
                    </div>

                    <!-- Proceed to Checkout Button -->
                    <a href="{{ route('checkout.index') }}" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center gap-2">
                        Proceed to Checkout →
                    </a>

                    <!-- Guarantee Badges -->
                    <div class="grid grid-cols-2 gap-2 text-center text-[10px] text-slate-600 pt-2">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 font-medium">
                            🔒 256-Bit SSL Checkout
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 font-medium">
                            📦 Fast Shipping
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
