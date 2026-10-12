@extends('layouts.app')

@section('title', 'Discover Artisan Makers & Tech Innovators')

@section('content')
<div class="space-y-16 lg:space-y-24">

    <!-- Hero Section (Clean White & Soft Indigo Light Theme) -->
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/60 via-white to-white text-slate-900 pt-10 pb-20 lg:pt-16 lg:pb-28 border-b border-slate-100">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-purple-200/30 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 text-indigo-700 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        Curated Online Marketplace
                    </div>

                    <h1 class="font-heading font-extrabold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.1] text-slate-900">
                        Shop directly from <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">verified makers & tech brands</span>.
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg max-w-xl leading-relaxed">
                        Discover handcrafted lifestyle goods and cutting-edge tech from independent creators. Safe checkout and guaranteed delivery worldwide.
                    </p>

                    <!-- Action Buttons -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base shadow-lg shadow-indigo-600/20 hover:scale-105 transition-all text-center">
                            Explore Products
                        </a>
                        <a href="{{ route('stores.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-base shadow-sm hover:scale-105 transition-all text-center">
                            Explore Stores
                        </a>
                    </div>

                    <!-- Clean E-Commerce Trust Badges -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-slate-200 text-left">
                        <div>
                            <div class="text-2xl font-bold font-heading text-slate-900">100%</div>
                            <div class="text-xs text-slate-500 font-medium">Verified Sellers</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold font-heading text-indigo-600">Free</div>
                            <div class="text-xs text-slate-500 font-medium">Shipping Over $99</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold font-heading text-purple-600">24/7</div>
                            <div class="text-xs text-slate-500 font-medium">Customer Support</div>
                        </div>
                    </div>
                </div>

                <!-- Featured Spotlight Product Card -->
                @if($featuredProducts->isNotEmpty())
                    @php $heroProduct = $featuredProducts->first(); @endphp
                    <div class="lg:col-span-5 relative">
                        <div class="relative mx-auto max-w-md bg-white p-6 rounded-3xl border border-slate-200 shadow-xl overflow-hidden group">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                                    Trending Pick
                                </span>
                                <span class="text-xs font-semibold text-amber-500 flex items-center gap-1">
                                    ★ {{ $heroProduct->rating_avg }}
                                </span>
                            </div>

                            <div class="mt-4 relative aspect-video rounded-2xl overflow-hidden bg-slate-50">
                                <img src="{{ $heroProduct->primary_image_url }}" alt="{{ $heroProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @if($heroProduct->discount_percent)
                                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-rose-600 text-white font-bold text-xs shadow-md">
                                        -{{ $heroProduct->discount_percent }}% OFF
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 space-y-2">
                                <div class="text-xs text-indigo-600 font-semibold">{{ $heroProduct->store->name }}</div>
                                <h3 class="font-heading font-extrabold text-slate-900 text-lg sm:text-xl truncate">
                                    {{ $heroProduct->name }}
                                </h3>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <div>
                                        <span class="text-2xl font-heading font-extrabold text-slate-900">${{ number_format($heroProduct->price, 2) }}</span>
                                        @if($heroProduct->compare_price)
                                            <span class="text-xs text-slate-400 line-through ml-1">${{ number_format($heroProduct->compare_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $heroProduct->slug) }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Browse Catalog</span>
                <h2 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight mt-1">Featured Categories</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-700 group">
                View All Categories
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                   class="group relative overflow-hidden rounded-3xl bg-white border border-slate-200/80 p-5 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 flex flex-col justify-between h-48">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 opacity-15 group-hover:opacity-25 group-hover:scale-110 transition-all duration-300">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-full">
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>

                    <div class="relative z-10">
                        <h3 class="font-heading font-bold text-slate-900 text-base group-hover:text-indigo-600 transition">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $category->products_count }} Products Available</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Trending & Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Editor's Picks</span>
                <h2 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight mt-1">Trending Products</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-700 group">
                Browse Entire Catalog
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    
                    <!-- Product Image & Badges -->
                    <div class="relative aspect-square overflow-hidden bg-slate-100">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Discount Badge -->
                        @if($product->discount_percent)
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-rose-600 text-white font-bold text-xs shadow-md">
                                -{{ $product->discount_percent }}%
                            </span>
                        @endif

                        <!-- Seller Badge -->
                        <a href="{{ route('stores.show', $product->store->slug) }}" class="absolute bottom-3 left-3 right-3 px-3 py-1.5 rounded-xl bg-white/95 hover:bg-white text-slate-800 text-[11px] font-medium shadow-sm border border-slate-200/80 backdrop-blur-md flex items-center justify-between transition">
                            <span class="truncate">Sold by <strong class="text-indigo-600">{{ $product->store->name }}</strong></span>
                            <span class="text-amber-500 font-bold flex items-center gap-0.5">★ {{ $product->rating_avg }}</span>
                        </a>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-xs text-indigo-600 font-semibold mb-1">{{ $product->category->name }}</div>
                            <h3 class="font-heading font-bold text-slate-900 text-base leading-snug hover:text-indigo-600 transition">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2 mt-1.5 leading-relaxed">{{ $product->short_description }}</p>
                        </div>

                        <!-- Price & Add to Cart -->
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <div class="text-lg font-heading font-extrabold text-slate-900">${{ number_format($product->price, 2) }}</div>
                                @if($product->compare_price)
                                    <div class="text-xs text-slate-600 line-through font-medium">${{ number_format($product->compare_price, 2) }}</div>
                                @endif
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 hover:scale-105 active:scale-95 transition flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </section>

    <!-- Top Rated Vendor Stores Spotlight (Clean Light Theme) -->
    <section class="bg-slate-50/60 py-16 border-y border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Independent Creators</span>
                    <h2 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight mt-1">Top Verified Vendors</h2>
                </div>
                <a href="{{ route('stores.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-700 group">
                    Explore All Vendor Stores
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($topStores as $store)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                        <!-- Store Banner -->
                        <div class="h-32 bg-slate-100 relative overflow-hidden">
                            <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-emerald-600 text-white font-bold text-[10px] uppercase shadow-sm">
                                Verified Vendor
                            </span>
                        </div>

                        <!-- Store Header & Info -->
                        <div class="p-6 pt-0 relative flex-1 flex flex-col justify-between">
                            <div>
                                <div class="-mt-10 mb-3 flex items-end justify-between">
                                    <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white">
                                    <span class="text-xs font-semibold text-slate-500">{{ $store->products_count }} active products</span>
                                </div>

                                <h3 class="font-heading font-extrabold text-slate-900 text-lg">
                                    <a href="{{ route('stores.show', $store->slug) }}" class="hover:text-indigo-600 transition">
                                        {{ $store->name }}
                                    </a>
                                </h3>
                                <p class="text-xs font-medium text-indigo-600 mt-0.5">{{ $store->tagline }}</p>
                                <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">{{ $store->description }}</p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ Str::limit($store->address ?? 'Verified Marketplace Seller', 26) }}
                                </span>
                                <a href="{{ route('stores.show', $store->slug) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-800 text-xs font-bold transition">
                                    Visit Shop →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Become a Vendor CTA Section (Clean White & Indigo Border Theme) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="rounded-3xl bg-gradient-to-r from-indigo-50/90 via-purple-50/60 to-white border border-indigo-100 text-slate-900 p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-lg">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-200/40 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-8 space-y-4 text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold border border-indigo-200">
                        ⭐ Start Selling Online
                    </span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight">
                        Grow your brand with us.
                    </h2>
                    <p class="text-slate-600 text-sm sm:text-base max-w-xl leading-relaxed">
                        Open your storefront in minutes. Reach thousands of daily shoppers with built-in store management and direct weekly payouts.
                    </p>
                </div>
                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
                    <a href="{{ route('vendor.register') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-center shadow-lg shadow-indigo-600/20 hover:scale-105 transition">
                        Open Your Store Today
                    </a>
                    <a href="{{ route('stores.index') }}" class="px-8 py-3 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-center text-xs shadow-xs transition">
                        Browse Verified Stores
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
