<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
        'is_featured',
        'status',
        'primary_image',
        'rating_avg',
        'reviews_count',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'rating_avg' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'reviews_count' => 'integer',
            'views_count' => 'integer',
            'track_stock' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereHas('store', function ($q) {
                         $q->where('status', 'approved');
                     });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('stock_quantity', '>', 0);
        });
    }

    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('short_description', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%");
        });
    }

    // Accessors
    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->primary_image) {
            if (Str::startsWith($this->primary_image, ['http://', 'https://'])) {
                return $this->primary_image;
            }
            return asset('storage/' . $this->primary_image);
        }

        $firstImage = $this->images->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80';
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return null;
    }

    public function getIsInStockAttribute(): bool
    {
        if (!$this->track_stock) {
            return true;
        }
        return $this->stock_quantity > 0;
    }

    public function updateRatingCache(): void
    {
        $avg = $this->reviews()->avg('rating') ?: 0.00;
        $count = $this->reviews()->count();

        $this->update([
            'rating_avg' => round($avg, 2),
            'reviews_count' => $count,
        ]);
    }
}
