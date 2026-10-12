<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function view(User $user, Order $order): bool
    {
        // Customer who made the order
        if ($order->user_id && $order->user_id === $user->id) {
            return true;
        }

        // Vendor who has at least one item in this order
        if ($user->isVendor() && $user->store) {
            return $order->items()->where('store_id', $user->store->id)->exists();
        }

        return false;
    }
}
