@extends('vendor.layout')

@section('title', 'Add New Product')

@section('vendor-content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Create Product</h1>
            <p class="text-xs text-slate-600 mt-1">Publish a new item to the OmniMarket catalog</p>
        </div>
        <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Products
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm">
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. AeroSonic Wireless Headphones"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Auto-generated if blank"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('sku')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price ($) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="49.99"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('price')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Compare at Price (Original / Discounted)</label>
                    <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}" placeholder="69.99"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required min="0"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Publishing Status *</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="published">Published (Live in catalog)</option>
                        <option value="draft">Draft (Hidden)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Primary Image (Upload or Web URL)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <input type="url" name="primary_image_url" value="{{ old('primary_image_url') }}" placeholder="Or paste image URL (e.g. Unsplash)"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Short Summary (1-2 sentences)</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" placeholder="Brief summary for catalog cards"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Detailed Description & Specifications</label>
                    <textarea name="description" rows="4" placeholder="Detailed product specifications, materials, and care..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('vendor.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                    Create Product
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
