<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\VendorPayoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiVendorMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $vendorUser1;
    protected Store $store1;
    protected User $vendorUser2;
    protected Store $store2;
    protected User $customer;
    protected Category $category;
    protected Product $product1;
    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::create([
            'name' => 'Marketplace Admin',
            'email' => 'admin@marketplace.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Vendor 1 (10% commission rate)
        $this->vendorUser1 = User::create([
            'name' => 'GearHub Vendor',
            'email' => 'tech@gearhub.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'status' => 'active',
        ]);
        $this->store1 = Store::create([
            'user_id' => $this->vendorUser1->id,
            'name' => 'GearHub Tech',
            'slug' => 'gearhub-tech',
            'commission_rate' => 10.00,
            'balance' => 0.00,
            'total_sales' => 0.00,
            'status' => 'approved',
        ]);

        // 3. Vendor 2 (15% commission rate)
        $this->vendorUser2 = User::create([
            'name' => 'DecorCraft Vendor',
            'email' => 'artisan@decorcraft.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'status' => 'active',
        ]);
        $this->store2 = Store::create([
            'user_id' => $this->vendorUser2->id,
            'name' => 'DecorCraft Living',
            'slug' => 'decorcraft-living',
            'commission_rate' => 15.00,
            'balance' => 0.00,
            'total_sales' => 0.00,
            'status' => 'approved',
        ]);

        // 4. Customer
        $this->customer = User::create([
            'name' => 'Sarah Customer',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        // 5. Category
        $this->category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'is_active' => true,
        ]);

        // 6. Products from different vendors
        $this->product1 = Product::create([
            'store_id' => $this->store1->id,
            'category_id' => $this->category->id,
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones',
            'sku' => 'GH-WH-01',
            'price' => 200.00,
            'stock_quantity' => 10,
            'track_stock' => true,
            'status' => 'published',
        ]);

        $this->product2 = Product::create([
            'store_id' => $this->store2->id,
            'category_id' => $this->category->id,
            'name' => 'Artisan Desk Lamp',
            'slug' => 'artisan-desk-lamp',
            'sku' => 'DC-DL-02',
            'price' => 100.00,
            'stock_quantity' => 5,
            'track_stock' => true,
            'status' => 'published',
        ]);
    }

    /**
     * Test public homepage and product browsing.
     */
    public function test_homepage_and_products_catalog_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('OmniMarket');

        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Wireless Headphones');
        $response->assertSee('Artisan Desk Lamp');
    }

    /**
     * Test public REST API for products and stores.
     */
    public function test_public_rest_api(): void
    {
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Wireless Headphones']);

        $response = $this->getJson('/api/v1/stores');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'GearHub Tech']);
    }

    /**
     * Test Multi-Vendor Cart calculations & grouping.
     */
    public function test_multi_vendor_cart_grouping_and_summary(): void
    {
        $cartService = app(CartService::class);

        // Add 1 unit from Vendor 1 ($200) and 2 units from Vendor 2 ($200)
        $cartService->addItem($this->product1->id, 1);
        $cartService->addItem($this->product2->id, 2);

        $this->assertEquals(3, $cartService->getCount());

        $grouped = $cartService->getItemsGroupedByStore();
        $this->assertCount(2, $grouped); // 2 distinct vendor packages
        $this->assertEquals(200.00, $grouped[$this->store1->id]['store_subtotal']);
        $this->assertEquals(200.00, $grouped[$this->store2->id]['store_subtotal']);

        $summary = $cartService->getSummary();
        $this->assertEquals(400.00, $summary['subtotal']);
    }

    /**
     * Test Multi-Vendor Checkout, Commission Calculation, and Vendor Balance updates.
     */
    public function test_multi_vendor_order_checkout_business_logic(): void
    {
        $this->actingAs($this->customer);

        $cartService = app(CartService::class);
        $orderService = app(OrderService::class);

        // Add items from 2 vendors
        $cartService->addItem($this->product1->id, 1); // $200 from Store 1 (10% commission)
        $cartService->addItem($this->product2->id, 1); // $100 from Store 2 (15% commission)

        $customerData = [
            'name' => 'Sarah Customer',
            'email' => 'sarah@example.com',
            'phone' => '555-1234',
            'address' => '123 Main St',
            'city' => 'Denver',
            'state' => 'CO',
            'zip' => '80203',
            'country' => 'United States',
        ];

        // Process instant card payment checkout
        $order = $orderService->createOrder($customerData, 'card');

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals(2, $order->items()->count());

        // Verify Vendor 1 Item calculations:
        // Subtotal = $200.00, Commission Rate = 10%, Admin Commission = $20.00, Vendor Earning = $180.00
        $item1 = $order->items()->where('store_id', $this->store1->id)->first();
        $this->assertNotNull($item1);
        $this->assertEquals(200.00, (float) $item1->subtotal);
        $this->assertEquals(10.00, (float) $item1->commission_rate);
        $this->assertEquals(20.00, (float) $item1->admin_commission);
        $this->assertEquals(180.00, (float) $item1->vendor_earning);

        // Verify Vendor 2 Item calculations:
        // Subtotal = $100.00, Commission Rate = 15%, Admin Commission = $15.00, Vendor Earning = $85.00
        $item2 = $order->items()->where('store_id', $this->store2->id)->first();
        $this->assertNotNull($item2);
        $this->assertEquals(100.00, (float) $item2->subtotal);
        $this->assertEquals(15.00, (float) $item2->commission_rate);
        $this->assertEquals(15.00, (float) $item2->admin_commission);
        $this->assertEquals(85.00, (float) $item2->vendor_earning);

        // Verify Store Balances updated accurately
        $this->store1->refresh();
        $this->store2->refresh();
        $this->assertEquals(180.00, (float) $this->store1->balance);
        $this->assertEquals(85.00, (float) $this->store2->balance);

        // Verify Store Ledger Transactions logged
        $this->assertEquals(1, StoreTransaction::where('store_id', $this->store1->id)->count());
        $this->assertEquals(1, StoreTransaction::where('store_id', $this->store2->id)->count());

        // Verify Stock decremented
        $this->product1->refresh();
        $this->product2->refresh();
        $this->assertEquals(9, $this->product1->stock_quantity);
        $this->assertEquals(4, $this->product2->stock_quantity);

        // Verify Cart is cleared
        $this->assertEquals(0, $cartService->getCount());
    }

    /**
     * Test Vendor Order Fulfillment Status Updates.
     */
    public function test_vendor_can_update_fulfillment_and_tracking(): void
    {
        $this->actingAs($this->customer);
        $cartService = app(CartService::class);
        $orderService = app(OrderService::class);

        $cartService->addItem($this->product1->id, 1);
        $order = $orderService->createOrder([
            'name' => 'Sarah',
            'email' => 'sarah@example.com',
            'address' => '123 Main St',
            'city' => 'Denver',
            'state' => 'CO',
            'zip' => '80203',
        ], 'card');

        $item = $order->items()->first();

        // Vendor fulfills the item
        $this->actingAs($this->vendorUser1);
        $response = $this->post("/vendor/orders/{$item->id}/status", [
            'fulfillment_status' => 'shipped',
            'shipping_courier' => 'FedEx Express',
            'tracking_number' => 'FDX-12345678',
        ]);

        $response->assertRedirect();
        $item->refresh();
        $this->assertEquals('shipped', $item->fulfillment_status);
        $this->assertEquals('FedEx Express', $item->shipping_courier);
        $this->assertEquals('FDX-12345678', $item->tracking_number);
    }

    /**
     * Test Vendor Payout Withdrawal and Admin Approval.
     */
    public function test_vendor_payout_withdrawal_lifecycle(): void
    {
        // Give vendor $500 balance
        $this->store1->update(['balance' => 500.00]);

        $payoutService = app(VendorPayoutService::class);

        // Vendor requests withdrawal of $200
        $txn = $payoutService->requestPayout($this->store1, 200.00, 'Monthly bank withdrawal');

        $this->assertEquals('pending', $txn->status);
        $this->assertEquals(200.00, (float) $txn->amount);

        // Balance deducted immediately to prevent double spending
        $this->store1->refresh();
        $this->assertEquals(300.00, (float) $this->store1->balance);

        // Admin approves the payout
        $this->actingAs($this->admin);
        $payoutService->approvePayout($txn, 'Bank wire ref #9988');

        $txn->refresh();
        $this->assertEquals('completed', $txn->status);
    }
}
