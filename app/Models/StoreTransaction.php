<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StoreTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'order_item_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'reference_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->reference_number)) {
                $transaction->reference_number = 'TXN-' . strtoupper(Str::random(10));
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'earning' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            'commission_deduction' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            'payout_withdrawal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            'refund' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        };
    }
}
