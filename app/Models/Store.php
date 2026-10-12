<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'tagline',
        'description',
        'logo',
        'banner',
        'phone',
        'email',
        'address',
        'commission_rate',
        'balance',
        'total_sales',
        'status',
        'bank_name',
        'account_name',
        'account_number',
        'swift_code',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
            'balance' => 'decimal:2',
            'total_sales' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = Str::slug($store->name) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StoreTransaction::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            if (Str::startsWith($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }
            return asset('storage/' . $this->logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0ea5e9&color=ffffff&bold=true';
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner) {
            if (Str::startsWith($this->banner, ['http://', 'https://'])) {
                return $this->banner;
            }
            return asset('storage/' . $this->banner);
        }
        return 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=80';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
