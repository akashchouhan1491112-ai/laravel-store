<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Process checkout and create multi-vendor order.
     */
    public function createOrder(array $customerData, string $paymentMethod = 'card'): Order
    {
        $items = $this->cartService->getItems();

        if ($items->isEmpty()) {
            throw new Exception('Your shopping cart is empty.');
        }

        $summary = $this->cartService->getSummary();
        $user = Auth::user();

        return DB::transaction(function () use ($items, $summary, $customerData, $paymentMethod, $user) {
            // 1. Validate Stock for all items first
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product || $product->status !== 'published') {
                    throw new Exception("Product '{$item->product->name}' is no longer available.");
                }
                if ($product->track_stock && $product->stock_quantity < $item->quantity) {
                    throw new Exception("Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}.");
                }
            }

            // 2. Determine payment status (Card/Stripe/Wallet are paid instantly, COD is unpaid)
            $isInstantPayment = in_array($paymentMethod, ['card', 'stripe', 'wallet', 'paypal']);
            $paymentStatus = $isInstantPayment ? 'paid' : 'unpaid';

            // 3. Create parent Order
            $order = Order::create([
                'user_id' => $user ? $user->id : null,
                'customer_name' => $customerData['name'],
                'customer_email' => $customerData['email'],
                'customer_phone' => $customerData['phone'] ?? null,
                'shipping_address' => $customerData['address'],
                'shipping_city' => $customerData['city'] ?? null,
                'shipping_state' => $customerData['state'] ?? null,
                'shipping_zip' => $customerData['zip'] ?? null,
                'shipping_country' => $customerData['country'] ?? 'United States',
                'subtotal' => $summary['subtotal'],
                'discount_amount' => $summary['discount'],
                'coupon_code' => $summary['coupon_code'],
                'shipping_fee' => $summary['shipping_fee'],
                'tax_amount' => $summary['tax_amount'],
                'total_amount' => $summary['total'],
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'order_status' => 'processing',
                'notes' => $customerData['notes'] ?? null,
            ]);

            // 4. Create multi-vendor Order Items and deduct inventory
            foreach ($items as $item) {
                $product = $item->product;
                $store = $product->store;

                $itemSubtotal = round($item->quantity * $product->price, 2);
                $commissionRate = (float) $store->commission_rate; // e.g. 10.00%
                $adminCommission = round(($itemSubtotal * $commissionRate) / 100, 2);
                $vendorEarning = round($itemSubtotal - $adminCommission, 2);

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $product->primary_image,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                    'commission_rate' => $commissionRate,
                    'admin_commission' => $adminCommission,
                    'vendor_earning' => $vendorEarning,
                    'fulfillment_status' => 'processing',
                ]);

                // Deduct stock
                if ($product->track_stock) {
                    $product->decrement('stock_quantity', $item->quantity);
                    if ($product->stock_quantity <= 0) {
                        $product->update(['status' => 'out_of_stock']);
                    }
                }

                // If paid instantly, credit store earnings & ledger
                if ($paymentStatus === 'paid') {
                    $this->creditVendorEarning($store, $orderItem);
                }
            }

            // 5. Update coupon usage
            if ($summary['coupon_code']) {
                $coupon = Coupon::where('code', $summary['coupon_code'])->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // 6. Clear shopping cart
            $this->cartService->clear();

            return $order->load(['items.store', 'items.product']);
        });
    }

    /**
     * Credit vendor balance and log store transaction.
     */
    public function creditVendorEarning(Store $store, OrderItem $orderItem): void
    {
        $balanceBefore = $store->balance;
        $balanceAfter = $balanceBefore + $orderItem->vendor_earning;

        $store->increment('balance', $orderItem->vendor_earning);
        $store->increment('total_sales', $orderItem->subtotal);

        StoreTransaction::create([
            'store_id' => $store->id,
            'order_item_id' => $orderItem->id,
            'type' => 'earning',
            'amount' => $orderItem->vendor_earning,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'status' => 'completed',
            'notes' => "Net earning for item #{$orderItem->id} ({$orderItem->product_name}) in order #{$orderItem->order->order_number}. Platform commission: \${$orderItem->admin_commission} ({$orderItem->commission_rate}%)",
        ]);
    }

    /**
     * Update vendor order item fulfillment status.
     */
    public function updateItemFulfillment(OrderItem $item, string $status, ?string $courier = null, ?string $tracking = null): OrderItem
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status: {$status}");
        }

        $item->update([
            'fulfillment_status' => $status,
            'shipping_courier' => $courier ?? $item->shipping_courier,
            'tracking_number' => $tracking ?? $item->tracking_number,
        ]);

        // If order was COD and just delivered, credit vendor earnings
        if ($status === 'delivered' && $item->order->payment_method === 'cod' && $item->order->payment_status !== 'paid') {
            $this->creditVendorEarning($item->store, $item);
        }

        // Check if all items in parent order are delivered or completed
        $parentOrder = $item->order;
        $allItemsDelivered = $parentOrder->items()->where('fulfillment_status', '!=', 'delivered')->doesntExist();

        if ($allItemsDelivered) {
            $parentOrder->update([
                'order_status' => 'completed',
                'payment_status' => 'paid',
            ]);
        }

        return $item;
    }

    /**
     * Cancel an order and restore inventory.
     */
    public function cancelOrder(Order $order, string $reason = ''): void
    {
        if (in_array($order->order_status, ['completed', 'cancelled'])) {
            throw new Exception("Cannot cancel an order that is already {$order->order_status}.");
        }

        DB::transaction(function () use ($order, $reason) {
            $order->update([
                'order_status' => 'cancelled',
                'notes' => trim($order->notes . "\nCancellation reason: " . $reason),
            ]);

            foreach ($order->items as $item) {
                $item->update(['fulfillment_status' => 'cancelled']);

                // Rollback product inventory
                if ($item->product && $item->product->track_stock) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    if ($item->product->status === 'out_of_stock') {
                        $item->product->update(['status' => 'published']);
                    }
                }

                // If vendor was credited previously, refund/deduct balance
                if ($order->payment_status === 'paid' && $item->vendor_earning > 0) {
                    $store = $item->store;
                    $balanceBefore = $store->balance;
                    $balanceAfter = max(0, $balanceBefore - $item->vendor_earning);

                    $store->decrement('balance', min($balanceBefore, $item->vendor_earning));
                    $store->decrement('total_sales', min($store->total_sales, $item->subtotal));

                    StoreTransaction::create([
                        'store_id' => $store->id,
                        'order_item_id' => $item->id,
                        'type' => 'refund',
                        'amount' => -$item->vendor_earning,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'status' => 'completed',
                        'notes' => "Refund deduction for cancelled order #{$order->order_number}",
                    ]);
                }
            }
        });
    }
}
