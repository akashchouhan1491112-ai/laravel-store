@extends('admin.layout')

@section('title', 'Edit Category - ' . $category->name)

@section('admin-content')
<div class="max-w-2xl space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Edit Category</h1>
            <p class="text-xs text-slate-600 mt-1">Slug: <strong class="font-mono text-slate-900">{{ $category->slug }}</strong></p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            ← Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Parent Category</label>
                <select name="parent_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                    <option value="">None (Top-Level Category)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category Image</label>
                <div class="flex items-center gap-3 mb-2">
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-xl object-cover ring-1 ring-slate-200">
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                        <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="Or new image URL"
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                <textarea name="description" rows="2"
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }} class="rounded text-indigo-600">
                    <span>Feature on Marketplace Homepage</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-7 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
