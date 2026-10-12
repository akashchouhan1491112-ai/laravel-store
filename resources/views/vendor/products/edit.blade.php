@extends('vendor.layout')

@section('title', 'Edit ' . $product->name)

@section('vendor-content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Edit Product</h1>
            <p class="text-xs text-slate-600 mt-1">Update SKU: <strong class="font-mono text-slate-900">{{ $product->sku }}</strong></p>
        </div>
        <a href="{{ route('vendor.products.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Products
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm">
        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price ($) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Compare Price (Original)</label>
                    <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        <option value="published" {{ $product->status === 'published' ? 'selected' : '' }}>Published (Live)</option>
                        <option value="draft" {{ $product->status === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        <option value="out_of_stock" {{ $product->status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Primary Image</label>
                    <div class="flex items-center gap-4 mb-2">
                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-xl object-cover ring-1 ring-slate-200">
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="file" name="primary_image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                            <input type="url" name="primary_image_url" value="{{ old('primary_image_url') }}" placeholder="Or new image URL"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Detailed Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">{{ old('description', $product->description) }}</textarea>
                </div>

            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('vendor.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
