<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $category = Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' created successfully!");
    }

    public function edit(Category $category): View
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' updated successfully!");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', "Category '{$name}' deleted.");
    }
}
