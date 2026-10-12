@extends('layouts.app')

@section('title', $store->name . ' - Storefront')

@section('content')
<div class="space-y-10">

    <!-- Storefront Cover Banner & Profile Card (Clean Light Theme) -->
    <div class="relative bg-slate-100 border-b border-slate-200/80">
        <div class="h-64 sm:h-80 w-full overflow-hidden relative">
            <img src="{{ $store->banner_url }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-24 pb-8">
            <div class="bg-white text-slate-900 rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <img src="{{ $store->logo_url }}" alt="{{ $store->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl object-cover ring-4 ring-white shadow-lg bg-white shrink-0">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">
                                ✓ Verified Merchant
                            </span>
                            <span class="text-xs text-slate-600">Joined {{ $store->created_at->format('M Y') }}</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">{{ $store->name }}</h1>
                        <p class="text-xs font-semibold text-indigo-600">{{ $store->tagline }}</p>
                        <p class="text-xs text-slate-600 max-w-xl leading-relaxed">{{ $store->description }}</p>
                    </div>
                </div>

                <!-- Store KPI / Contact Quick Info -->
                <div class="flex items-center gap-6 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-8 text-center shrink-0">
                    <div>
                        <div class="font-heading font-extrabold text-2xl text-slate-900">{{ $products->total() }}</div>
                        <div class="text-[11px] text-slate-600">Active Products</div>
                    </div>
                    <div>
                        <div class="font-heading font-extrabold text-2xl text-amber-500">4.9 ★</div>
                        <div class="text-[11px] text-slate-600">Store Rating</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Store Catalog with Category Filter & Search -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-2 overflow-x-auto pb-2">
                <a href="{{ route('stores.show', $store->slug) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    All Store Items ({{ $products->total() }})
                </a>
                @foreach($storeCategories as $cat)
                    <a href="{{ route('stores.show', [$store->slug, 'category' => $cat->slug]) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('stores.show', $store->slug) }}" method="GET" class="flex items-center gap-3">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search in this shop..." 
                       class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500">
                <select name="sort" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low</option>
                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High</option>
                    <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Top Rated</option>
                </select>
            </form>
        </div>

        @if($products->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                <p class="text-sm text-slate-600">No products found for this category or search.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                        <div class="relative aspect-square overflow-hidden bg-slate-100">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @if($product->discount_percent)
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-rose-600 text-white font-bold text-xs shadow-md">
                                    -{{ $product->discount_percent }}%
                                </span>
                            @endif
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-xs text-indigo-600 font-semibold mb-1">{{ $product->category->name }}</div>
                                <h3 class="font-heading font-bold text-slate-900 text-base leading-snug hover:text-indigo-600 transition">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-xs text-slate-600 line-clamp-2 mt-1">{{ $product->short_description }}</p>
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
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                                        Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
