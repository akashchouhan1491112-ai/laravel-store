@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @php
        $vendorStore = Auth::user()->store;
    @endphp

    <!-- Store Approval Warning if Pending -->
    @if($vendorStore && $vendorStore->status === 'pending')
        <div class="mb-8 p-5 rounded-3xl bg-amber-500/10 border border-amber-500/30 text-amber-900 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                <div>
                    <strong class="font-heading font-bold text-sm block">Vendor Account Pending Administrator Approval</strong>
                    <span class="text-xs text-amber-800">Your store profile has been submitted. Platform admins will verify and approve your account shortly.</span>
                </div>
            </div>
            <div class="px-3.5 py-1.5 rounded-xl bg-amber-100 border border-amber-300 text-amber-900 font-semibold text-xs shrink-0">
                Verification in progress
            </div>
        </div>
    @elseif($vendorStore && $vendorStore->status === 'suspended')
        <div class="mb-8 p-5 rounded-3xl bg-rose-500/10 border border-rose-500/30 text-rose-900 flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
            <div>
                <strong class="font-heading font-bold text-sm block">Store Account Suspended</strong>
                <span class="text-xs text-rose-800">Your vendor account is suspended. Please contact marketplace administration for compliance verification.</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Vendor Sidebar (3 cols) -->
        <div class="lg:col-span-3 space-y-4 sticky top-28">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                
                @if($vendorStore)
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <img src="{{ $vendorStore->logo_url }}" alt="{{ $vendorStore->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-indigo-50 shrink-0">
                        <div class="min-w-0">
                            <h3 class="font-heading font-bold text-sm text-slate-900 truncate">{{ $vendorStore->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $vendorStore->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $vendorStore->status }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Navigation Links -->
                <nav class="space-y-1.5 font-medium text-xs">
                    <a href="{{ route('vendor.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('vendor.dashboard') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Vendor Dashboard
                    </a>

                    <a href="{{ route('vendor.products.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('vendor.products.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Products Catalog
                    </a>

                    <a href="{{ route('vendor.orders.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('vendor.orders.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Orders & Fulfillment
                    </a>

                    <a href="{{ route('vendor.payouts.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('vendor.payouts.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Earnings & Payouts
                    </a>

                    <a href="{{ route('vendor.settings') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('vendor.settings') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Store Settings & Bank
                    </a>
                </nav>

                @if($vendorStore && $vendorStore->status === 'approved')
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('stores.show', $vendorStore->slug) }}" target="_blank"
                           class="w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs flex items-center justify-center gap-1.5 transition">
                            <span>View Public Shop</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Vendor Content Area (9 cols) -->
        <div class="lg:col-span-9">
            @yield('vendor-content')
        </div>

    </div>

</div>
@endsection
