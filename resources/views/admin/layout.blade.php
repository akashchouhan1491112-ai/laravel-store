@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

    @php
        $pendingStores = \App\Models\Store::where('status', 'pending')->count();
        $pendingPayouts = \App\Models\StoreTransaction::where('type', 'payout_withdrawal')->where('status', 'pending')->count();
    @endphp

    <!-- Mobile Horizontal Sticky Navigation (Swipeable / Scrollable) -->
    <div class="lg:hidden mb-6 -mx-4 px-4 overflow-x-auto no-scrollbar">
        <div class="flex items-center gap-2 min-w-max pb-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Overview
            </a>

            <a href="{{ route('admin.vendors.index') }}" 
               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Vendors
                @if($pendingStores > 0)
                    <span class="px-1.5 py-0.2 rounded-full bg-amber-500 text-white font-bold text-[9px]">{{ $pendingStores }}</span>
                @endif
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Categories
            </a>

            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Orders
            </a>

            <a href="{{ route('admin.payouts.index') }}" 
               class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.payouts.*') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Payouts
                @if($pendingPayouts > 0)
                    <span class="px-1.5 py-0.2 rounded-full bg-rose-600 text-white font-bold text-[9px]">{{ $pendingPayouts }}</span>
                @endif
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Desktop Admin Sidebar (3 cols) -->
        <div class="hidden lg:block lg:col-span-3 space-y-4 sticky top-28">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-rose-500/20">
                        ⚡
                    </div>
                    <div>
                        <h3 class="font-heading font-extrabold text-sm text-slate-900">Admin Control</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 uppercase">
                            Platform Admin
                        </span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 font-medium text-xs">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Admin Overview
                    </a>

                    <a href="{{ route('admin.vendors.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Vendors & Stores
                        </div>
                        @if($pendingStores > 0)
                            <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white font-bold text-[10px]">{{ $pendingStores }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Category Management
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        All Orders & GMV
                    </a>

                    <a href="{{ route('admin.payouts.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.payouts.*') ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Payout Approvals
                        </div>
                        @if($pendingPayouts > 0)
                            <span class="px-2 py-0.5 rounded-full bg-rose-600 text-white font-bold text-[10px]">{{ $pendingPayouts }}</span>
                        @endif
                    </a>
                </nav>

                <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-500">
                    <div>Logged in as: <strong class="text-slate-900">{{ Auth::user()->name }}</strong></div>
                </div>
            </div>
        </div>

        <!-- Admin Content (9 cols) -->
        <div class="lg:col-span-9 w-full min-w-0">
            @yield('admin-content')
        </div>

    </div>

</div>
@endsection
