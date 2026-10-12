@extends('admin.layout')

@section('title', 'Manage Categories')

@section('admin-content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Category Taxonomy</h1>
            <p class="text-xs text-slate-600 mt-1">Organize marketplace product classifications and navigation trees</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Category
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-600 uppercase font-bold tracking-wider border-b border-slate-100">
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Slug</th>
                        <th class="pb-3">Parent</th>
                        <th class="pb-3">Products</th>
                        <th class="pb-3">Featured</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($categories as $category)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200 shrink-0">
                                    <span class="font-heading font-bold text-slate-900 text-sm">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 font-mono text-slate-600">{{ $category->slug }}</td>
                            <td class="py-3.5 text-slate-600">{{ $category->parent ? $category->parent->name : 'Root Category' }}</td>
                            <td class="py-3.5 font-bold text-slate-900">{{ $category->products_count }} products</td>
                            <td class="py-3.5">
                                @if($category->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">★ Featured</span>
                                @else
                                    <span class="text-slate-600 text-xs">Standard</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600" title="Delete">
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
            {{ $categories->links() }}
        </div>
    </div>

</div>
@endsection
