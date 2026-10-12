@extends('admin.layout')

@section('title', 'Order Audit ' . $order->order_number)

@section('admin-content')
<div class="max-w-4xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Order Audit #{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-600 mt-1">Multi-vendor item commission split and customer delivery records</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Orders
        </a>
    </div>

    <!-- Line Items & Multi-Vendor Commission Splitting Audit -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="font-heading font-extrabold text-lg text-slate-900 pb-4 border-b border-slate-100 flex items-center justify-between">
            <span>Multi-Vendor Line Item & Commission Ledger</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $order->status_badge_class }}">
                {{ $order->order_status }}
            </span>
        </h3>

        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 text-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                        <div>
                            <span class="font-heading font-bold text-sm text-slate-900">{{ $item->product_name }}</span>
                            <span class="text-slate-600 block">Sold by Vendor Store: <strong class="text-indigo-600">{{ $item->store->name }}</strong></span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $item->fulfillment_badge_class }}">
                            {{ $item->fulfillment_status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                        <div>
                            <span class="text-slate-600 block">Item Subtotal:</span>
                            <strong class="text-slate-900">${{ number_format($item->subtotal, 2) }} ({{ $item->quantity }}x)</strong>
                        </div>
                        <div>
                            <span class="text-slate-600 block">Vendor Comm Rate:</span>
                            <strong class="text-indigo-600">{{ $item->commission_rate }}%</strong>
                        </div>
                        <div>
                            <span class="text-slate-600 block">Platform Commission:</span>
                            <strong class="text-emerald-600">${{ number_format($item->admin_commission, 2) }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-600 block">Vendor Net Earning:</span>
                            <strong class="text-slate-900">${{ number_format($item->vendor_earning, 2) }}</strong>
                        </div>
                    </div>

                    @if($item->tracking_number)
                        <div class="pt-2 text-[11px] text-slate-600 border-t border-slate-200/60 font-mono">
                            Carrier: {{ $item->shipping_courier }} | Tracking: {{ $item->tracking_number }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Customer and Financials Summary -->
        <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <h4 class="font-heading font-bold text-slate-900 uppercase">Customer & Delivery Address</h4>
                <p class="text-slate-700 leading-relaxed">
                    {{ $order->customer_name }} ({{ $order->customer_email }})<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                </p>
                <div class="pt-1">Payment Method: <strong class="uppercase text-slate-900">{{ $order->payment_method }}</strong> ({{ $order->payment_status }})</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-right">
                <h4 class="font-heading font-bold text-slate-900 uppercase text-left sm:text-right">Order Financial Totals</h4>
                <div class="flex justify-between text-slate-600">
                    <span>Gross Subtotal:</span>
                    <span class="font-bold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Discount:</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600">
                    <span>Shipping + Tax:</span>
                    <span class="font-bold text-slate-900">${{ number_format($order->shipping_fee + $order->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                    <span>Total Charged:</span>
                    <span class="text-indigo-600 font-extrabold text-lg">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Admin Order Cancellation / Stock Rollback -->
        @if(!in_array($order->order_status, ['completed', 'cancelled']))
            <div class="pt-6 border-t border-slate-100">
                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Cancel this entire order and restore product stock?');" class="flex items-center justify-between">
                    @csrf
                    <div>
                        <span class="font-bold text-rose-600 text-xs block">Cancel Order & Restore Inventory</span>
                        <span class="text-[11px] text-slate-600">Reverses vendor earnings and increments product stock quantity.</span>
                    </div>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition">
                        Cancel Order & Refund
                    </button>
                </form>
            </div>
        @endif

    </div>

</div>
@endsection
