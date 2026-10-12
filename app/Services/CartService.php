<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get current session ID for guest carts.
     */
    protected function getSessionId(): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', Session::getId() ?: uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    /**
     * Get cart items query for current user or guest.
     */
    protected function getCartQuery()
    {
        if (Auth::check()) {
            return CartItem::with(['product.store', 'product.images'])->where('user_id', Auth::id());
        }

        return CartItem::with(['product.store', 'product.images'])->where('session_id', $this->getSessionId());
    }

    /**
     * Get all cart items.
     */
    public function getItems(): Collection
    {
        return $this->getCartQuery()->get();
    }

    /**
     * Get cart items grouped by vendor/store.
     */
    public function getItemsGroupedByStore(): array
    {
        $items = $this->getItems();
        $grouped = [];

        foreach ($items as $item) {
            $store = $item->product->store;
            $storeId = $store->id;

            if (!isset($grouped[$storeId])) {
                $grouped[$storeId] = [
                    'store' => $store,
                    'items' => [],
                    'store_subtotal' => 0,
                ];
            }

            $grouped[$storeId]['items'][] = $item;
            $grouped[$storeId]['store_subtotal'] += $item->subtotal;
        }

        return $grouped;
    }

    /**
     * Add product to cart.
     */
    public function addItem(int $productId, int $quantity = 1): array
    {
        $product = Product::with('store')->find($productId);

        if (!$product || $product->status !== 'published' || !$product->store->isApproved()) {
            return ['success' => false, 'message' => 'Product is currently unavailable.'];
        }

        if ($product->track_stock && $product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$product->stock_quantity} units available in stock."];
        }

        $userId = Auth::id();
        $sessionId = $userId ? null : $this->getSessionId();

        $cartItem = CartItem::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->where('product_id', $productId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->track_stock && $product->stock_quantity < $newQuantity) {
                return ['success' => false, 'message' => "Cannot add more. You have {$cartItem->quantity} in cart and stock is {$product->stock_quantity}."];
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return [
            'success' => true,
            'message' => "Added '{$product->name}' to your cart!",
            'cart_count' => $this->getCount(),
            'cart_subtotal' => $this->getSummary()['subtotal'],
        ];
    }

    /**
     * Update cart item quantity.
     */
    public function updateQuantity(int $cartItemId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartItemId);
        }

        $cartItem = $this->getCartQuery()->where('id', $cartItemId)->first();

        if (!$cartItem) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $product = $cartItem->product;
        if ($product->track_stock && $product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => "Only {$product->stock_quantity} units available."];
        }

        $cartItem->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => 'Cart updated successfully.',
            'item_subtotal' => $cartItem->subtotal,
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $cartItemId): array
    {
        $cartItem = $this->getCartQuery()->where('id', $cartItemId)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return [
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart_count' => $this->getCount(),
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Clear all items from cart.
     */
    public function clear(): void
    {
        $this->getCartQuery()->delete();
        Session::forget('applied_coupon');
    }

    /**
     * Get total number of items in cart.
     */
    public function getCount(): int
    {
        return (int) $this->getCartQuery()->sum('quantity');
    }

    /**
     * Apply coupon code.
     */
    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();
        $subtotal = $this->getSubtotal();

        if (!$coupon || !$coupon->isValidForAmount($subtotal)) {
            return ['success' => false, 'message' => 'Invalid or expired coupon code.'];
        }

        Session::put('applied_coupon', $coupon->code);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): array
    {
        Session::forget('applied_coupon');
        return [
            'success' => true,
            'message' => 'Coupon removed.',
            'summary' => $this->getSummary(),
        ];
    }

    /**
     * Get cart subtotal.
     */
    public function getSubtotal(): float
    {
        $items = $this->getItems();
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += $item->subtotal;
        }
        return round($subtotal, 2);
    }

    /**
     * Get full cart price summary.
     */
    public function getSummary(): array
    {
        $subtotal = $this->getSubtotal();
        $discount = 0.0;
        $couponCode = Session::get('applied_coupon');
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                Session::forget('applied_coupon');
                $couponCode = null;
            }
        }

        // Standard marketplace shipping: $5 flat or free over $100
        $shippingFee = ($subtotal > 0 && $subtotal < 100) ? 9.99 : 0.00;
        // Estimated tax: 5% on taxable amount
        $taxableAmount = max(0, $subtotal - $discount);
        $taxAmount = round($taxableAmount * 0.05, 2);
        $total = max(0, round($taxableAmount + $shippingFee + $taxAmount, 2));

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_code' => $couponCode,
            'coupon' => $coupon,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'count' => $this->getCount(),
        ];
    }

    /**
     * Transfer guest cart items to logged-in user on login.
     */
    public function transferGuestCartToUser(User $user): void
    {
        $sessionId = Session::get('cart_session_id');
        if (!$sessionId) {
            return;
        }

        $guestItems = CartItem::where('session_id', $sessionId)->get();

        foreach ($guestItems as $guestItem) {
            $existingUserItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingUserItem) {
                $existingUserItem->update([
                    'quantity' => $existingUserItem->quantity + $guestItem->quantity,
                ]);
                $guestItem->delete();
            } else {
                $guestItem->update([
                    'user_id' => $user->id,
                    'session_id' => null,
                ]);
            }
        }

        Session::forget('cart_session_id');
    }
}
