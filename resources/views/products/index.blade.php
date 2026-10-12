@extends('layouts.app')

@section('title', 'Browse Marketplace Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
            <span class="mx-2 text-slate-300">/</span>
            <span class="text-slate-800">All Products</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">All Products</h1>
                <p class="text-xs text-slate-500 mt-1">Showing {{ $products->total() }} items</p>
            </div>

            <!-- Sort Controls -->
            <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-3">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    @if(is_array($val))
                        @foreach($val as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <label for="sort" class="text-xs font-bold text-slate-600 shrink-0">Sort By:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Top Customer Rated</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm sticky top-28 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="font-heading font-bold text-base text-slate-900">Filters</h3>
                    @if(request()->hasAny(['category', 'store', 'min_price', 'max_price', 'q', 'in_stock']))
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-rose-600 hover:underline">Reset All</a>
                    @endif
                </div>

                <form action="{{ route('products.index') }}" method="GET" class="space-y-6">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <!-- Search Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Search Term</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search keywords..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Categories Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Categories</label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            <label class="flex items-center gap-2 text-xs text-slate-700 hover:text-indigo-600 cursor-pointer">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="text-indigo-600">
                                <span>All Categories</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2 text-xs text-slate-700 hover:text-indigo-600 cursor-pointer">
                                    <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'checked' : '' }} class="text-indigo-600">
                                    <span class="truncate">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Vendor Stores Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Vendor Stores</label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            <label class="flex items-center gap-2 text-xs text-slate-700 hover:text-indigo-600 cursor-pointer">
                                <input type="radio" name="store" value="" {{ !request('store') ? 'checked' : '' }} class="text-indigo-600">
                                <span>All Vendors</span>
                            </label>
                            @foreach($stores as $store)
                                <label class="flex items-center gap-2 text-xs text-slate-700 hover:text-indigo-600 cursor-pointer">
                                    <input type="radio" name="store" value="{{ $store->slug }}" {{ request('store') === $store->slug ? 'checked' : '' }} class="text-indigo-600">
                                    <span class="truncate">{{ $store->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Price Range ($)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" class="w-1/2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                            <span class="text-slate-400">-</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" class="w-1/2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        </div>
                    </div>

                    <!-- In Stock Checkbox -->
                    <div>
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-800 cursor-pointer">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="rounded text-indigo-600">
                            <span>In Stock Only</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-slate-900 mb-1">No products match your criteria</h3>
                    <p class="text-xs text-slate-600 mb-6">Try clearing some filter criteria or search terms.</p>
                    <a href="{{ route('products.index') }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs">
                        View All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            
                            <!-- Product Image -->
                            <div class="relative aspect-square overflow-hidden bg-slate-100">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                @if($product->discount_percent)
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-rose-600 text-white font-bold text-xs shadow-md">
                                        -{{ $product->discount_percent }}%
                                    </span>
                                @endif

                                <a href="{{ route('stores.show', $product->store->slug) }}" class="absolute bottom-3 left-3 right-3 px-3 py-1.5 rounded-xl bg-white/95 hover:bg-white text-slate-800 text-[11px] font-medium shadow-sm border border-slate-200/80 backdrop-blur-md flex items-center justify-between transition">
                                    <span class="truncate">Sold by <strong class="text-indigo-600">{{ $product->store->name }}</strong></span>
                                    <span class="text-amber-500 font-bold flex items-center gap-0.5">★ {{ $product->rating_avg }}</span>
                                </a>
                            </div>

                            <!-- Product Content -->
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
                                            Add
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
