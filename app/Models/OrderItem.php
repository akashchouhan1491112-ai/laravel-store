<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'store_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_image',
        'price',
        'quantity',
        'subtotal',
        'commission_rate',
        'admin_commission',
        'vendor_earning',
        'fulfillment_status',
        'tracking_number',
        'shipping_courier',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'admin_commission' => 'decimal:2',
            'vendor_earning' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getProductImageUrlAttribute(): string
    {
        if ($this->product_image) {
            if (Str::startsWith($this->product_image, ['http://', 'https://'])) {
                return $this->product_image;
            }
            return asset('storage/' . $this->product_image);
        }
        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=300&q=80';
    }

    public function getFulfillmentBadgeClassAttribute(): string
    {
        return match ($this->fulfillment_status) {
            'delivered' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            'shipped' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        };
    }
}
