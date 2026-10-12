@extends('vendor.layout')

@section('title', 'Order Item #' . $item->id)

@section('vendor-content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Fulfill Order Item</h1>
            <p class="text-xs text-slate-600 mt-1">Parent Order: <strong class="font-mono text-slate-900">{{ $item->order->order_number }}</strong></p>
        </div>
        <a href="{{ route('vendor.orders.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Orders
        </a>
    </div>

    <!-- Fulfillment Status Update Form -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm space-y-6">
        
        <!-- Product & Financial Breakdown -->
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
            <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-2xl object-cover bg-white ring-1 ring-slate-200 shrink-0">
            <div class="flex-1 min-w-0">
                <div class="text-[11px] font-bold text-indigo-600 uppercase">Product Details</div>
                <h3 class="font-heading font-bold text-base text-slate-900 truncate">{{ $item->product_name }}</h3>
                <div class="text-xs text-slate-600">Quantity: <strong>{{ $item->quantity }}</strong> | Unit Price: <strong>${{ number_format($item->price, 2) }}</strong></div>
            </div>
            <div class="text-right">
                <div class="text-[10px] text-slate-600">Your Net Earning</div>
                <div class="font-heading font-extrabold text-xl text-emerald-600">${{ number_format($item->vendor_earning, 2) }}</div>
                <div class="text-[10px] text-slate-600 font-medium">Platform Fee: ${{ number_format($item->admin_commission, 2) }} ({{ $item->commission_rate }}%)</div>
            </div>
        </div>

        <!-- Customer Delivery Information -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs space-y-2">
            <div class="font-heading font-bold text-slate-900 uppercase tracking-wider text-xs">Customer Shipping Address</div>
            <div class="text-slate-700 leading-relaxed font-medium">
                {{ $item->order->customer_name }}<br>
                {{ $item->order->shipping_address }}<br>
                {{ $item->order->shipping_city }}, {{ $item->order->shipping_state }} {{ $item->order->shipping_zip }}, {{ $item->order->shipping_country }}
            </div>
            @if($item->order->customer_phone)
                <div class="text-slate-600">Phone: {{ $item->order->customer_phone }}</div>
            @endif
        </div>

        <!-- Update Status Action Form -->
        <form action="{{ route('vendor.orders.status', $item->id) }}" method="POST" class="space-y-4 pt-2 border-t border-slate-100">
            @csrf

            <h4 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600">
                Update Fulfillment Progress
            </h4>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Fulfillment Status *</label>
                <select name="fulfillment_status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="processing" {{ $item->fulfillment_status === 'processing' ? 'selected' : '' }}>Processing (Packaging in warehouse)</option>
                    <option value="shipped" {{ $item->fulfillment_status === 'shipped' ? 'selected' : '' }}>Shipped (Handed over to carrier)</option>
                    <option value="delivered" {{ $item->fulfillment_status === 'delivered' ? 'selected' : '' }}>Delivered (Confirmed received by customer)</option>
                    <option value="cancelled" {{ $item->fulfillment_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Shipping Courier</label>
                    <input type="text" name="shipping_courier" value="{{ old('shipping_courier', $item->shipping_courier) }}" placeholder="e.g. FedEx, UPS, DHL, USPS"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $item->tracking_number) }}" placeholder="e.g. 9400111899562849102834"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Update Tracking & Fulfillment
            </button>
        </form>

    </div>

</div>
@endsection
