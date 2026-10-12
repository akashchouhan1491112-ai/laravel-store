<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Store $store): bool
    {
        if ($store->status === 'approved') {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $store->user_id;
    }

    public function update(User $user, Store $store): bool
    {
        return $user->id === $store->user_id;
    }

    public function managePayouts(User $user, Store $store): bool
    {
        return $user->id === $store->user_id && $store->status === 'approved';
    }
}
