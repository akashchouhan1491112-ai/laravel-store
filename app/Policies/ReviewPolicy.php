<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user, Product $product): bool
    {
        // Verified purchase check: user must have an order with this product delivered or completed
        return \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('payment_status', 'paid');
            })
            ->where('fulfillment_status', 'delivered')
            ->exists();
    }
}
