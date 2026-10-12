<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');
        return $this->user() && ($this->user()->isAdmin() || ($this->user()->isVendor() && $this->user()->store && $this->user()->store->id === $product->store_id));
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', 'unique:products,sku,' . $productId],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'compare_price' => ['nullable', 'numeric', 'min:0.01', 'gt:price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'track_stock' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:published,draft,archived,out_of_stock'],
            'primary_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'primary_image_url' => ['nullable', 'url'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
