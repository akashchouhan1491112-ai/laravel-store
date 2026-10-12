@extends('admin.layout')

@section('title', 'Admin Platform Dashboard')

@section('admin-content')
<div class="space-y-8">

    <div>
        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight">Platform Control Center</h1>
        <p class="text-xs text-slate-600 mt-1">Real-time marketplace revenue, vendor commissions, and approval workflows</p>
    </div>

    <!-- Platform KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Platform Gross GMV</span>
            <div class="font-heading font-extrabold text-2xl text-slate-900">${{ number_format($platformGmv, 2) }}</div>
            <div class="text-[11px] text-emerald-600 font-semibold">Total settled volume</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Platform Commission</span>
            <div class="font-heading font-extrabold text-2xl text-indigo-600">${{ number_format($totalCommissionEarned, 2) }}</div>
            <div class="text-[11px] text-indigo-500 font-semibold">Marketplace net earnings</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Total Vendors</span>
            <div class="font-heading font-extrabold text-2xl text-slate-900">{{ $totalVendorsCount }}</div>
            <div class="text-[11px] text-slate-600 font-semibold">{{ $pendingVendorsCount }} pending verification</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm space-y-1">
            <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Pending Payouts</span>
            <div class="font-heading font-extrabold text-2xl text-amber-500">{{ $pendingPayoutsCount }}</div>
            <div class="text-[11px] text-amber-600 font-semibold">
                <a href="{{ route('admin.payouts.index') }}" class="hover:underline">Review requests →</a>
            </div>
        </div>

    </div>

    <!-- Pending Vendor Approvals Section -->
    @if($pendingStores->count() > 0)
        <div class="bg-amber-50/70 border border-amber-200 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    <h3 class="font-heading font-bold text-base text-amber-950">Pending Vendor Applications (Action Required)</h3>
                </div>
                <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="text-xs font-bold text-amber-900 hover:underline">
                    View All ({{ $pendingVendorsCount }}) →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pendingStores as $pStore)
                    <div class="bg-white p-5 rounded-2xl border border-amber-200 shadow-sm flex flex-col justify-between space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $pStore->logo_url }}" alt="{{ $pStore->name }}" class="w-12 h-12 rounded-xl object-cover ring-1 ring-amber-200 shrink-0">
                                <div>
                                    <h4 class="font-heading font-bold text-sm text-slate-900">{{ $pStore->name }}</h4>
                                    <div class="text-[11px] text-slate-600">Owner: {{ $pStore->user->name }} ({{ $pStore->user->email }})</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold uppercase">Pending</span>
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ $pStore->description }}</p>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-600">Commission: <strong>{{ $pStore->commission_rate }}%</strong></span>
                            
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.vendors.status', $pStore->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                                        ✓ Approve Store
                                    </button>
                                </form>
                                <form action="{{ route('admin.vendors.status', $pStore->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Platform Orders -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-heading font-extrabold text-lg text-slate-900">Recent Marketplace Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                View All Orders →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                        <th class="pb-3">Order #</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Vendors Involved</th>
                        <th class="pb-3">Total Amount</th>
                        <th class="pb-3">Payment</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 font-mono text-slate-900 font-bold">{{ $order->order_number }}</td>
                            <td class="py-3.5 text-slate-900 font-bold">{{ $order->customer_name }}</td>
                            <td class="py-3.5 text-indigo-600 font-semibold">
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
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] transition">
                                    View Audit →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
