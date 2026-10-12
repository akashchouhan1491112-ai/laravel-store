@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ 
    selectedImage: '{{ $product->primary_image_url }}',
    quantity: 1,
    activeTab: 'description'
}">

    <!-- Breadcrumbs -->
    <nav class="flex text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a>
        <span class="mx-2 text-slate-300">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-indigo-600">{{ $product->category->name }}</a>
        <span class="mx-2 text-slate-300">/</span>
        <span class="text-slate-800 truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    <!-- Product Showcase Top Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm">
        
        <!-- Product Image Gallery (5 cols) -->
        <div class="lg:col-span-6 space-y-4">
            <!-- Main Hero Image Display -->
            <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center relative">
                <img :src="selectedImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300">
                @if($product->discount_percent)
                    <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-rose-600 text-white font-bold text-xs shadow-md">
                        -{{ $product->discount_percent }}% OFF
                    </span>
                @endif
            </div>

            <!-- Thumbnail Carousel / Strip -->
            @if($product->images->count() > 0)
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    <button type="button" @click="selectedImage = '{{ $product->primary_image_url }}'"
                            :class="selectedImage === '{{ $product->primary_image_url }}' ? 'ring-2 ring-indigo-600' : 'opacity-70 hover:opacity-100'"
                            class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 shrink-0 transition">
                        <img src="{{ $product->primary_image_url }}" alt="Thumb" class="w-full h-full object-cover">
                    </button>
                    @foreach($product->images as $img)
                        <button type="button" @click="selectedImage = '{{ $img->image_url }}'"
                                :class="selectedImage === '{{ $img->image_url }}' ? 'ring-2 ring-indigo-600' : 'opacity-70 hover:opacity-100'"
                                class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 shrink-0 transition">
                            <img src="{{ $img->image_url }}" alt="Gallery image" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Purchase Information (7 cols) -->
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                
                <!-- Category and Rating -->
                <div class="flex items-center justify-between gap-4">
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider">
                        {{ $product->category->name }}
                    </span>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-amber-500 font-bold flex items-center gap-1">
                            ★ {{ number_format($product->rating_avg, 1) }}
                        </span>
                        <span class="text-slate-400">•</span>
                        <a href="#reviews" @click="activeTab = 'reviews'" class="text-slate-500 hover:text-indigo-600 underline font-medium">
                            {{ $product->reviews_count }} verified reviews
                        </a>
                    </div>
                </div>

                <!-- Product Name -->
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 tracking-tight leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- Price and Availability -->
                <div class="flex items-baseline gap-4">
                    <div class="font-heading font-extrabold text-3xl text-slate-900">
                        ${{ number_format($product->price, 2) }}
                    </div>
                    @if($product->compare_price)
                        <div class="text-base text-slate-400 line-through font-medium">
                            ${{ number_format($product->compare_price, 2) }}
                        </div>
                    @endif
                    <div class="ml-auto">
                        @if($product->is_in_stock)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                In Stock ({{ $product->stock_quantity }} available)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Out of Stock
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Short description -->
                <p class="text-sm text-slate-600 leading-relaxed pt-2">
                    {{ $product->short_description }}
                </p>

                <!-- SKU & Info meta -->
                <div class="text-xs text-slate-400 space-y-1 pt-2 border-t border-slate-100">
                    <div>SKU: <span class="font-mono text-slate-600 font-semibold">{{ $product->sku }}</span></div>
                    <div>Ships within 24-48 hours via Vendor Direct Delivery</div>
                </div>

                <!-- Add to Cart / Quantity Form -->
                <form action="{{ route('cart.add') }}" method="POST" class="pt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center gap-4">
                        <label class="text-xs font-bold text-slate-700 uppercase">Quantity:</label>
                        <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                            <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="px-3 py-2 text-slate-600 hover:bg-slate-200 transition font-bold text-sm">-</button>
                            <input type="number" name="quantity" x-model="quantity" min="1" max="{{ $product->stock_quantity }}" class="w-12 text-center bg-transparent border-none text-xs font-bold text-slate-900 focus:outline-none">
                            <button type="button" @click="quantity = Math.min({{ $product->stock_quantity }}, quantity + 1)" class="px-3 py-2 text-slate-600 hover:bg-slate-200 transition font-bold text-sm">+</button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" {{ !$product->is_in_stock ? 'disabled' : '' }}
                                class="flex-1 py-4 px-8 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Add to Cart (${{ number_format($product->price, 2) }})
                        </button>
                    </div>
                </form>

            </div>

            <!-- Seller / Vendor Box Spotlight -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="{{ $product->store->logo_url }}" alt="{{ $product->store->name }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-white shadow-sm shrink-0">
                    <div class="min-w-0">
                        <div class="text-[11px] font-bold text-indigo-600 uppercase">Verified Merchant</div>
                        <h4 class="font-heading font-bold text-slate-900 text-sm truncate">
                            <a href="{{ route('stores.show', $product->store->slug) }}" class="hover:underline">
                                {{ $product->store->name }}
                            </a>
                        </h4>
                        <div class="text-xs text-slate-500 truncate">{{ $product->store->tagline }}</div>
                    </div>
                </div>

                <a href="{{ route('stores.show', $product->store->slug) }}" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 text-slate-800 text-xs font-bold transition shrink-0 shadow-sm">
                    Visit Shop →
                </a>
            </div>

        </div>

    </div>

    <!-- Product Details / Vendor Info / Verified Reviews Tabs -->
    <div id="reviews" class="mt-12 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-sm">
        
        <!-- Tab Navigation -->
        <div class="flex items-center gap-4 sm:gap-8 border-b border-slate-200 pb-4 text-sm font-bold">
            <button type="button" @click="activeTab = 'description'"
                    :class="activeTab === 'description' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-4 -mb-4' : 'text-slate-500 hover:text-slate-800'">
                Full Description
            </button>
            <button type="button" @click="activeTab = 'vendor'"
                    :class="activeTab === 'vendor' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-4 -mb-4' : 'text-slate-500 hover:text-slate-800'">
                About the Maker & Store
            </button>
            <button type="button" @click="activeTab = 'reviews'"
                    :class="activeTab === 'reviews' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-4 -mb-4' : 'text-slate-500 hover:text-slate-800'">
                Customer Reviews ({{ $product->reviews_count }})
            </button>
        </div>

        <!-- Tab 1: Description -->
        <div x-show="activeTab === 'description'" class="pt-6 space-y-4 text-sm text-slate-700 leading-relaxed">
            <h3 class="font-heading font-bold text-lg text-slate-900">Product Overview</h3>
            <p>{{ $product->description ?: $product->short_description }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
                <div class="p-4 rounded-xl bg-slate-50">
                    <strong class="text-slate-900 block mb-1">Authenticity Guaranteed</strong>
                    Directly sourced and packaged by the certified vendor store.
                </div>
                <div class="p-4 rounded-xl bg-slate-50">
                    <strong class="text-slate-900 block mb-1">Hassle-Free Returns</strong>
                    14-day buyer protection policy on all marketplace orders.
                </div>
            </div>
        </div>

        <!-- Tab 2: Vendor Info -->
        <div x-show="activeTab === 'vendor'" class="pt-6 space-y-4">
            <div class="flex items-center gap-4">
                <img src="{{ $product->store->logo_url }}" alt="{{ $product->store->name }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                <div>
                    <h3 class="font-heading font-bold text-xl text-slate-900">{{ $product->store->name }}</h3>
                    <p class="text-xs text-indigo-600 font-medium">{{ $product->store->tagline }}</p>
                    <p class="text-xs text-slate-500">{{ $product->store->address }}</p>
                </div>
            </div>
            <p class="text-sm text-slate-700 leading-relaxed pt-2">{{ $product->store->description }}</p>
            <div class="pt-4">
                <a href="{{ route('stores.show', $product->store->slug) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm">
                    View All Products from {{ $product->store->name }} →
                </a>
            </div>
        </div>

        <!-- Tab 3: Customer Reviews -->
        <div x-show="activeTab === 'reviews'" class="pt-6 space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="font-heading font-extrabold text-4xl text-slate-900">{{ number_format($product->rating_avg, 1) }}</span>
                        <div>
                            <div class="text-amber-500 text-base font-bold">★★★★★</div>
                            <div class="text-xs text-slate-500">Based on {{ $product->reviews_count }} verified buyer ratings</div>
                        </div>
                    </div>
                </div>

                <!-- Review submission prompt or button -->
                @auth
                    <div class="text-right">
                        <button type="button" @click="$refs.reviewForm.classList.toggle('hidden')" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                            Write a Review
                        </button>
                    </div>
                @else
                    <div>
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                            Sign in to review
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Review Form (Auth only) -->
            @auth
                <div x-ref="reviewForm" class="hidden p-6 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-4">
                    <h4 class="font-heading font-bold text-base text-slate-900">Leave Your Verified Review</h4>
                    <form action="{{ route('customer.reviews.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Rating</label>
                                <select name="rating" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="5">★★★★★ (5 Stars - Excellent)</option>
                                    <option value="4">★★★★☆ (4 Stars - Very Good)</option>
                                    <option value="3">★★★☆☆ (3 Stars - Average)</option>
                                    <option value="2">★★☆☆☆ (2 Stars - Below Average)</option>
                                    <option value="1">★☆☆☆☆ (1 Star - Poor)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Review Headline</label>
                                <input type="text" name="title" placeholder="e.g. Exceeded my expectations!" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Your Detailed Feedback</label>
                            <textarea name="comment" rows="3" required placeholder="Share your experience with build quality, delivery, and seller service..." class="w-full bg-white border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition">
                            Submit Review
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Reviews List -->
            @if($product->reviews->isEmpty())
                <div class="text-center py-8 text-slate-500 text-xs">
                    No customer reviews yet. Be the first to review this product!
                </div>
            @else
                <div class="space-y-4 divide-y divide-slate-100">
                    @foreach($product->reviews as $review)
                        <div class="pt-4 first:pt-0 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ $review->user->name }}</div>
                                        <div class="text-[10px] text-emerald-600 font-semibold">✓ Verified Buyer</div>
                                    </div>
                                </div>
                                <span class="text-amber-500 font-bold text-xs">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </span>
                            </div>

                            @if($review->title)
                                <div class="font-bold text-slate-900 text-xs">{{ $review->title }}</div>
                            @endif

                            <p class="text-xs text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                            <div class="text-[10px] text-slate-400">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h3 class="font-heading font-extrabold text-2xl text-slate-900 mb-6">You Might Also Like</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    <div class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl transition overflow-hidden flex flex-col justify-between">
                        <div class="aspect-square bg-slate-100 overflow-hidden relative">
                            <img src="{{ $rel->primary_image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] text-indigo-600 font-semibold uppercase">{{ $rel->category->name }}</div>
                                <h4 class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition truncate">
                                    <a href="{{ route('products.show', $rel->slug) }}">{{ $rel->name }}</a>
                                </h4>
                            </div>
                            <div class="pt-3 flex items-center justify-between">
                                <span class="font-heading font-bold text-slate-900 text-base">${{ number_format($rel->price, 2) }}</span>
                                <a href="{{ route('products.show', $rel->slug) }}" class="text-xs font-bold text-indigo-600 hover:underline">View →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
