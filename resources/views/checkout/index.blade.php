@extends('layouts.app')

@section('title', 'Secure Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">Checkout</h1>
        <p class="text-xs text-slate-500 mt-1">Please enter your shipping address and payment details</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Delivery & Payment Form (7 cols) -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Shipping Details Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-5">
                    <h3 class="font-heading font-bold text-lg text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                        Shipping Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name *</label>
                            <input type="text" name="name" value="{{ old('name', $user ? $user->name : '') }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', $user ? $user->email : '') }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user ? $user->phone : '') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', 'United States') }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Street Address *</label>
                            <input type="text" name="address" value="{{ old('address', $user ? $user->address : '') }}" required placeholder="123 Main Street, Apt 4B"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City *</label>
                            <input type="text" name="city" value="{{ old('city', 'Denver') }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">State *</label>
                                <input type="text" name="state" value="{{ old('state', 'CO') }}" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">ZIP *</label>
                                <input type="text" name="zip" value="{{ old('zip', '80203') }}" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" placeholder="Special delivery instructions or gate code..."
                                      class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
                    <h3 class="font-heading font-bold text-lg text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                        Payment Method
                    </h3>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-indigo-600 bg-indigo-50/40 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="card" checked class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">Credit / Debit Card</div>
                                    <div class="text-[11px] text-slate-500">Secure payment processing (Visa, Mastercard, American Express)</div>
                                </div>
                            </div>
                            <span class="text-emerald-600 text-xs font-bold">🔒 Encrypted</span>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">Cash on Delivery (COD)</div>
                                    <div class="text-[11px] text-slate-500">Pay upon package delivery</div>
                                </div>
                            </div>
                            <span class="text-slate-400 text-xs font-medium">📦 Pay on Arrival</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Order Review & Summary (5 cols) -->
            <div class="lg:col-span-5 space-y-6 sticky top-28">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                    <h3 class="font-heading font-extrabold text-xl text-slate-900 pb-4 border-b border-slate-100">
                        Order Summary
                    </h3>

                    <!-- Grouped Items Preview -->
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                        @foreach($groupedItems as $group)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60 space-y-2.5 text-xs">
                                <div class="flex items-center justify-between font-bold text-slate-900 border-b border-slate-200/60 pb-2">
                                    <span class="text-indigo-600 truncate">{{ $group['store']->name }}</span>
                                    <span>${{ number_format($group['store_subtotal'], 2) }}</span>
                                </div>
                                @foreach($group['items'] as $item)
                                    <div class="flex items-center justify-between gap-2 text-slate-600">
                                        <span class="truncate">{{ $item->quantity }}x {{ $item->product->name }}</span>
                                        <span class="font-semibold text-slate-900">${{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Totals -->
                    <div class="space-y-2.5 text-xs pt-4 border-t border-slate-100">
                        <div class="flex justify-between text-slate-600">
                            <span>Items Subtotal</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['subtotal'], 2) }}</span>
                        </div>
                        @if($summary['discount'] > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span>Discount ({{ $summary['coupon_code'] }})</span>
                                <span>-${{ number_format($summary['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping</span>
                            <span class="font-bold text-slate-900">{{ $summary['shipping_fee'] == 0 ? 'FREE' : '$' . number_format($summary['shipping_fee'], 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Estimated Tax</span>
                            <span class="font-bold text-slate-900">${{ number_format($summary['tax_amount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-3 border-t border-slate-100 text-sm font-bold text-slate-900">
                            <span>Grand Total</span>
                            <span class="font-heading font-extrabold text-2xl text-indigo-600">${{ number_format($summary['total'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition">
                        Place Order (${{ number_format($summary['total'], 2) }})
                    </button>

                    <p class="text-[11px] text-center text-slate-400 leading-normal">
                        By placing your order, you agree to our Terms of Service and Privacy Policy.
                    </p>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
