@extends('layouts.app')

@section('title', 'Verified Vendor Stores Directory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-10 text-center max-w-2xl mx-auto space-y-3">
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight">
            Verified Stores
        </h1>
        <p class="text-sm text-slate-500 leading-relaxed">
            Discover independent brands, artisan workshops, and creators.
        </p>

        <!-- Store Search Input -->
        <form action="{{ route('stores.index') }}" method="GET" class="max-w-md mx-auto relative pt-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search vendor stores by name or specialty..."
                   class="w-full pl-11 pr-24 py-3 bg-white border border-slate-200 rounded-2xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 shadow-sm transition">
            <div class="absolute inset-y-0 left-0 pl-4 pt-2 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="absolute inset-y-3.5 right-2 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                Search
            </button>
        </form>
    </div>

    <!-- Stores Directory Grid -->
    @if($stores->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
            <p class="text-sm text-slate-600">No vendor stores found matching your search.</p>
            <a href="{{ route('stores.index') }}" class="mt-4 inline-block px-5 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs">
                View All Stores
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stores as $store)
                <div class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <!-- Banner -->
                    <div class="h-36 bg-slate-100 relative overflow-hidden">
                        <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-emerald-600 text-white font-bold text-[10px] uppercase shadow-sm">
                            ✓ Verified Vendor
                        </span>
                    </div>

                    <!-- Store Details -->
                    <div class="p-6 pt-0 relative flex-1 flex flex-col justify-between">
                        <div>
                            <div class="-mt-10 mb-4 flex items-end justify-between">
                                <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="w-18 h-18 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white">
                                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">
                                    {{ $store->products_count }} Products
                                </span>
                            </div>

                            <h3 class="font-heading font-extrabold text-xl text-slate-900">
                                <a href="{{ route('stores.show', $store->slug) }}" class="hover:text-indigo-600 transition">
                                    {{ $store->name }}
                                </a>
                            </h3>
                            <p class="text-xs font-semibold text-indigo-600 mt-0.5">{{ $store->tagline }}</p>
                            <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">{{ $store->description }}</p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <span class="text-xs text-slate-600 flex items-center gap-1 truncate">
                                <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ Str::limit($store->address ?? 'Verified Marketplace Merchant', 24) }}
                            </span>
                            <a href="{{ route('stores.show', $store->slug) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-indigo-600 text-white text-xs font-bold transition shadow-sm shrink-0">
                                Visit Storefront →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $stores->links() }}
        </div>
    @endif

</div>
@endsection
