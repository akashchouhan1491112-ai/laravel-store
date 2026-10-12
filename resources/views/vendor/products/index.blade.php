@extends('vendor.layout')

@section('title', 'My Products')

@section('vendor-content')
<div class="space-y-6">

    <!-- Header & Create Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Products Catalog</h1>
            <p class="text-xs text-slate-600 mt-1">Manage your storefront's inventory, pricing, and availability</p>
        </div>

        <a href="{{ route('vendor.products.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Product
        </a>
    </div>

    <!-- Product List Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        
        <!-- Filters -->
        <form action="{{ route('vendor.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 pb-4 border-b border-slate-100">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, SKU..." class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <select name="category" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>
        </form>

        @if($products->isEmpty())
            <div class="text-center py-12 text-slate-600 text-xs">
                No products found. <a href="{{ route('vendor.products.create') }}" class="text-indigo-600 font-bold hover:underline">Add your first product now</a>.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                            <th class="pb-3">Product</th>
                            <th class="pb-3">Category</th>
                            <th class="pb-3">Price</th>
                            <th class="pb-3">Stock</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($products as $product)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-xl object-cover bg-slate-100 shrink-0">
                                        <div class="min-w-0">
                                            <div class="font-heading font-bold text-slate-900 truncate">{{ $product->name }}</div>
                                            <div class="text-[10px] text-slate-600 font-mono">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 text-slate-600">{{ $product->category->name }}</td>
                                <td class="py-3.5 font-bold text-slate-900">
                                    ${{ number_format($product->price, 2) }}
                                    @if($product->compare_price)
                                        <span class="text-[10px] text-slate-600 line-through block font-normal">${{ number_format($product->compare_price, 2) }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5">
                                    @if($product->stock_quantity > 0)
                                        <span class="text-slate-900 font-bold">{{ $product->stock_quantity }}</span> units
                                    @else
                                        <span class="text-rose-600 font-bold">0 (Out)</span>
                                    @endif
                                </td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $product->status === 'published' ? 'bg-emerald-100 text-emerald-800' : ($product->status === 'draft' ? 'bg-slate-100 text-slate-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-slate-800" title="View live">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('vendor.products.edit', $product->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600" title="Edit product">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600" title="Delete product">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
