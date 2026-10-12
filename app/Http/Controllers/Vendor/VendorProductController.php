<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\ProductStoreRequest;
use App\Http\Requests\Vendor\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VendorProductController extends Controller
{
    public function index(Request $request): View
    {
        $store = Auth::user()->store;
        $query = Product::where('store_id', $store->id)->with('category');

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::active()->get();

        return view('vendor.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $store = Auth::user()->store;

        $data = $request->validated();
        $data['store_id'] = $store->id;
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['track_stock'] = $request->boolean('track_stock', true);

        // Handle primary image upload or url
        if ($request->hasFile('primary_image')) {
            $data['primary_image'] = $request->file('primary_image')->store('products', 'public');
        } elseif ($request->filled('primary_image_url')) {
            $data['primary_image'] = $request->primary_image_url;
        }

        $product = Product::create($data);

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', "Product '{$product->name}' created successfully!");
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);
        $categories = Category::active()->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['track_stock'] = $request->boolean('track_stock', true);

        if ($request->hasFile('primary_image')) {
            $data['primary_image'] = $request->file('primary_image')->store('products', 'public');
        } elseif ($request->filled('primary_image_url')) {
            $data['primary_image'] = $request->primary_image_url;
        }

        $product->update($data);

        // Handle gallery images addition
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $product->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')->with('success', "Product '{$product->name}' updated successfully!");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $name = $product->name;
        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', "Product '{$name}' deleted.");
    }
}
