<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Store;
use App\Models\StoreTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Administrator
        $admin = User::create([
            'name' => 'Alexander Vance',
            'email' => 'admin@marketplace.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1 (555) 019-2834',
            'status' => 'active',
            'address' => '742 Market Street, San Francisco, CA',
        ]);

        // 2. Create Vendors and Stores
        $vendor1User = User::create([
            'name' => 'Marcus Sterling',
            'email' => 'tech@gearhub.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'phone' => '+1 (555) 234-5678',
            'status' => 'active',
            'address' => '452 Innovation Way, Austin, TX',
        ]);

        $store1 = Store::create([
            'user_id' => $vendor1User->id,
            'name' => 'GearHub Technologies',
            'slug' => 'gearhub-technologies',
            'tagline' => 'Next-gen audio, smart accessories & premium workspace gear',
            'description' => 'GearHub is dedicated to crafting and curating ultra-high performance workspace tools, audiophile headphones, and wireless charging solutions for modern creators.',
            'logo' => 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=200&q=80',
            'banner' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1400&q=80',
            'phone' => '+1 (555) 234-5678',
            'email' => 'contact@gearhub.com',
            'address' => '452 Innovation Way, Austin, TX 78701',
            'commission_rate' => 10.00, // 10% platform fee
            'balance' => 849.50,
            'total_sales' => 1420.00,
            'status' => 'approved',
            'bank_name' => 'Silicon Valley Bank',
            'account_name' => 'GearHub Technologies LLC',
            'account_number' => '**** **** 4892',
            'swift_code' => 'SVBUS6S',
        ]);

        $vendor2User = User::create([
            'name' => 'Elena Rostova',
            'email' => 'artisan@decorcraft.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'phone' => '+1 (555) 876-5432',
            'status' => 'active',
            'address' => '18 Ceramic Lane, Portland, OR',
        ]);

        $store2 = Store::create([
            'user_id' => $vendor2User->id,
            'name' => 'DecorCraft Living',
            'slug' => 'decorcraft-living',
            'tagline' => 'Handcrafted ceramics, ambient lighting & organic minimalist home goods',
            'description' => 'We curate warm, timeless aesthetic pieces for the intentional home. Every ceramic piece is hand-thrown and kiln-fired by independent artisan potters.',
            'logo' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=200&q=80',
            'banner' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1400&q=80',
            'phone' => '+1 (555) 876-5432',
            'email' => 'support@decorcraft.com',
            'address' => '18 Ceramic Lane, Portland, OR 97201',
            'commission_rate' => 12.00, // 12% platform fee
            'balance' => 420.00,
            'total_sales' => 780.00,
            'status' => 'approved',
            'bank_name' => 'Chase Bank',
            'account_name' => 'DecorCraft Studio LLC',
            'account_number' => '**** **** 1934',
            'swift_code' => 'CHASUS33',
        ]);

        $vendor3User = User::create([
            'name' => 'Kai Thorne',
            'email' => 'style@urbanthreads.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'phone' => '+1 (555) 345-9876',
            'status' => 'active',
            'address' => '88 Soho District, New York, NY',
        ]);

        $store3 = Store::create([
            'user_id' => $vendor3User->id,
            'name' => 'Urban Threads Co.',
            'slug' => 'urban-threads-co',
            'tagline' => 'Sustainable street fashion, heavyweight cotton basics & minimal outerwear',
            'description' => 'Urban Threads is dedicated to 100% organic fair-trade apparel with modern oversized silhouettes and ethical manufacturing standards.',
            'logo' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=200&q=80',
            'banner' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1400&q=80',
            'phone' => '+1 (555) 345-9876',
            'email' => 'orders@urbanthreads.com',
            'address' => '88 Soho District, New York, NY 10012',
            'commission_rate' => 8.00, // 8% platform fee
            'balance' => 310.00,
            'total_sales' => 520.00,
            'status' => 'approved',
            'bank_name' => 'Bank of America',
            'account_name' => 'Urban Threads Apparel',
            'account_number' => '**** **** 7721',
            'swift_code' => 'BOFAUS3N',
        ]);

        // Pending Vendor for demoing Admin approval
        $vendor4User = User::create([
            'name' => 'Julian Hayes',
            'email' => 'pending@newvendor.com',
            'password' => Hash::make('password123'),
            'role' => 'vendor',
            'phone' => '+1 (555) 999-1234',
            'status' => 'active',
            'address' => '100 Cybernetic Ave, Seattle, WA',
        ]);

        Store::create([
            'user_id' => $vendor4User->id,
            'name' => 'Summit Trail & Mountain Gear',
            'slug' => 'summit-trail-gear',
            'tagline' => 'All-weather trekking packs, camping tents, and mountain accessories',
            'description' => 'Awaiting platform review to introduce premium trekking backpacks, waterproof tents, and all-weather trail accessories.',
            'logo' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=200&q=80',
            'banner' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1400&q=80',
            'commission_rate' => 10.00,
            'balance' => 0.00,
            'total_sales' => 0.00,
            'status' => 'pending', // Pending Admin Approval!
        ]);

        // 3. Create Customers
        $customer1 = User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+1 (555) 444-5555',
            'status' => 'active',
            'address' => '124 Maple Drive, Denver, CO 80203',
        ]);

        $customer2 = User::create([
            'name' => 'Alex Rivera',
            'email' => 'alex@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+1 (555) 777-8888',
            'status' => 'active',
            'address' => '500 Sunset Blvd, Los Angeles, CA 90028',
        ]);

        // 4. Create Categories
        $catElectronics = Category::create([
            'name' => 'Electronics & Gadgets',
            'slug' => 'electronics-gadgets',
            'icon' => 'device-phone-mobile',
            'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=600&q=80',
            'description' => 'Premium audio, smart workspace tools, and wireless electronics',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $catHome = Category::create([
            'name' => 'Home & Living',
            'slug' => 'home-living',
            'icon' => 'home',
            'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=600&q=80',
            'description' => 'Artisan ceramics, lighting, and minimalist interior decor',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $catFashion = Category::create([
            'name' => 'Fashion & Apparel',
            'slug' => 'fashion-apparel',
            'icon' => 'sparkles',
            'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=600&q=80',
            'description' => 'Modern sustainable apparel, outerwear, and wardrobe essentials',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $catAccessories = Category::create([
            'name' => 'Accessories & Carry',
            'slug' => 'accessories-carry',
            'icon' => 'shopping-bag',
            'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80',
            'description' => 'Leather goods, everyday carry pouches, and travel essentials',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // 5. Create Products
        $productsData = [
            // Store 1: GearHub
            [
                'store_id' => $store1->id,
                'category_id' => $catElectronics->id,
                'name' => 'AeroSonic Wireless Noise-Cancelling Headphones',
                'slug' => 'aerosonic-wireless-headphones',
                'sku' => 'GH-AERO-01',
                'short_description' => 'Studio-grade acoustic transparency with active hybrid ANC and 40-hour battery life.',
                'description' => 'Engineered for audio purists and deep work focus. Featuring custom 45mm titanium drivers, plush memory foam earcups, ultra-low latency Bluetooth 5.3, and multi-device seamless switching.',
                'price' => 249.00,
                'compare_price' => 299.00,
                'stock_quantity' => 28,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.9,
                'reviews_count' => 12,
                'views_count' => 340,
            ],
            [
                'store_id' => $store1->id,
                'category_id' => $catElectronics->id,
                'name' => 'MagStack 3-in-1 Magnetic Wireless Charging Stand',
                'slug' => 'magstack-3-in-1-charging-stand',
                'sku' => 'GH-MAG-02',
                'short_description' => 'Simultaneous fast charging for Phone, Watch, and Earbuds in CNC machined aluminum.',
                'description' => 'Streamline your nightstand or executive desk. Features 15W Qi2 certified magnetic fast charging, ambient LED sleep indicator, and weighted anti-slip solid base.',
                'price' => 79.00,
                'compare_price' => 99.00,
                'stock_quantity' => 45,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.8,
                'reviews_count' => 8,
                'views_count' => 210,
            ],
            [
                'store_id' => $store1->id,
                'category_id' => $catElectronics->id,
                'name' => 'PulseMechanical Custom Wireless Keyboard (Gateron Yellow)',
                'slug' => 'pulse-mechanical-keyboard',
                'sku' => 'GH-KEY-03',
                'short_description' => 'Hot-swappable 75% mechanical keyboard with sound-dampened gasket mount.',
                'description' => 'A dream typing experience with pre-lubed Gateron Yellow switches, dye-sub PBT keycaps, per-key RGB backlighting, and 4000mAh battery for up to 200 hours of cordless typing.',
                'price' => 149.00,
                'compare_price' => 179.00,
                'stock_quantity' => 14,
                'is_featured' => false,
                'primary_image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 5.0,
                'reviews_count' => 6,
                'views_count' => 180,
            ],
            [
                'store_id' => $store1->id,
                'category_id' => $catElectronics->id,
                'name' => 'AuraGlow Smart Desk Ambient Lightbar',
                'slug' => 'auraglow-smart-lightbar',
                'sku' => 'GH-LGT-04',
                'short_description' => 'Asymmetric glare-free monitor lightbar with wireless rotary controller dial.',
                'description' => 'Reduces eye strain during nighttime creative sessions. Auto-dimming sensor, adjustable color temperatures (2700K - 6500K), and sleek aerospace aluminum alloy chassis.',
                'price' => 65.00,
                'compare_price' => null,
                'stock_quantity' => 30,
                'is_featured' => false,
                'primary_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.7,
                'reviews_count' => 4,
                'views_count' => 95,
            ],

            // Store 2: DecorCraft Living
            [
                'store_id' => $store2->id,
                'category_id' => $catHome->id,
                'name' => 'Nordic Terracotta Ceramic Table Lamp',
                'slug' => 'nordic-terracotta-table-lamp',
                'sku' => 'DC-LMP-01',
                'short_description' => 'Hand-turned organic terracotta base with textured natural linen drum shade.',
                'description' => 'Bring calming earthy textures to your bedroom or living room. Finished with a matte mineral glaze and fitted with an energy-efficient warm filament bulb.',
                'price' => 120.00,
                'compare_price' => 150.00,
                'stock_quantity' => 18,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.9,
                'reviews_count' => 9,
                'views_count' => 280,
            ],
            [
                'store_id' => $store2->id,
                'category_id' => $catHome->id,
                'name' => 'Handmade Wabi-Sabi Ceramic Vase Set (3-Piece)',
                'slug' => 'handmade-wabi-sabi-vase-set',
                'sku' => 'DC-VAS-02',
                'short_description' => 'Trio of sculptural stone-glazed stoneware vases for dried florals and botanicals.',
                'description' => 'Each piece exhibits subtle natural variations in texture and shade. Kiln-fired at 1280°C for exceptional durability and timeless organic beauty.',
                'price' => 85.00,
                'compare_price' => 110.00,
                'stock_quantity' => 22,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1612196808214-b8e1d6145a8c?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 5.0,
                'reviews_count' => 11,
                'views_count' => 310,
            ],
            [
                'store_id' => $store2->id,
                'category_id' => $catHome->id,
                'name' => 'Solid Oak Minimalist Floating Wall Shelf',
                'slug' => 'solid-oak-floating-shelf',
                'sku' => 'DC-SHF-03',
                'short_description' => 'Handcrafted European white oak with concealed heavy-duty steel mounting hardware.',
                'description' => 'Treated with organic beeswax and natural botanical oils to accentuate the rich natural wood grain. Supports up to 40 lbs of books, ceramics, and artwork.',
                'price' => 74.00,
                'compare_price' => null,
                'stock_quantity' => 15,
                'is_featured' => false,
                'primary_image' => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.6,
                'reviews_count' => 5,
                'views_count' => 120,
            ],

            // Store 3: Urban Threads Co.
            [
                'store_id' => $store3->id,
                'category_id' => $catFashion->id,
                'name' => 'Heavyweight 400GSM Organic Cotton Oversized Hoodie',
                'slug' => 'heavyweight-organic-cotton-hoodie',
                'sku' => 'UT-HOD-01',
                'short_description' => 'Pre-shrunk French Terry cotton with double-layered hood and relaxed drape.',
                'description' => 'The ultimate everyday hoodie. Custom milled 400GSM loopback cotton, seamless ribbed cuffs, kangaroo pocket with hidden audio cord slit, and garment dyed for a rich vintage wash.',
                'price' => 88.00,
                'compare_price' => 110.00,
                'stock_quantity' => 35,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.8,
                'reviews_count' => 15,
                'views_count' => 410,
            ],
            [
                'store_id' => $store3->id,
                'category_id' => $catFashion->id,
                'name' => 'Minimal Canvas Chore Jacket in Raw Natural',
                'slug' => 'canvas-chore-jacket-natural',
                'sku' => 'UT-JKT-02',
                'short_description' => '12oz durable duck canvas work jacket with antique brass custom tack buttons.',
                'description' => 'Designed for transitional weather. Features 3 exterior patch pockets, interior passport pocket, and reinforced elbow stitching built to age gracefully over years of wear.',
                'price' => 135.00,
                'compare_price' => 165.00,
                'stock_quantity' => 19,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 4.9,
                'reviews_count' => 7,
                'views_count' => 260,
            ],
            [
                'store_id' => $store3->id,
                'category_id' => $catAccessories->id,
                'name' => 'Full-Grain Vegetable Tanned Leather Everyday Tote',
                'slug' => 'leather-everyday-tote',
                'sku' => 'UT-TOT-03',
                'short_description' => 'Hand-stitched full grain Italian leather with dedicated 16-inch laptop compartment.',
                'description' => 'Crafted without synthetic fillers or linings. Develops a gorgeous golden patina over time. Includes key lanyard clip and solid brass hardware.',
                'price' => 195.00,
                'compare_price' => 240.00,
                'stock_quantity' => 12,
                'is_featured' => true,
                'primary_image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
                'rating_avg' => 5.0,
                'reviews_count' => 8,
                'views_count' => 320,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $product = Product::create($data);
            $createdProducts[] = $product;

            // Add gallery images
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->primary_image,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // 6. Create Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10.00,
            'min_spend' => 30.00,
            'max_discount' => 50.00,
            'usage_limit' => 500,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'MARKET20',
            'type' => 'percentage',
            'value' => 20.00,
            'min_spend' => 100.00,
            'max_discount' => 80.00,
            'usage_limit' => 200,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'type' => 'fixed',
            'value' => 10.00,
            'min_spend' => 50.00,
            'usage_limit' => 300,
            'is_active' => true,
        ]);

        // 7. Create Past Orders to demonstrate Multi-Vendor Order Splitting & Vendor Earnings
        $p1 = $createdProducts[0]; // Headphones from GearHub ($249.00)
        $p5 = $createdProducts[4]; // Terracotta Lamp from DecorCraft ($120.00)
        $p8 = $createdProducts[7]; // Organic Hoodie from Urban Threads ($88.00)

        // Order 1: Multi-Vendor Order (Customer Sarah bought from GearHub AND DecorCraft in 1 cart!)
        $order1 = Order::create([
            'order_number' => 'ORD-' . strtoupper(date('Ymd')) . '-782190',
            'user_id' => $customer1->id,
            'customer_name' => 'Sarah Jenkins',
            'customer_email' => 'sarah@example.com',
            'customer_phone' => '+1 (555) 444-5555',
            'shipping_address' => '124 Maple Drive',
            'shipping_city' => 'Denver',
            'shipping_state' => 'CO',
            'shipping_zip' => '80203',
            'shipping_country' => 'United States',
            'subtotal' => 369.00,
            'discount_amount' => 36.90, // WELCOME10 coupon
            'coupon_code' => 'WELCOME10',
            'shipping_fee' => 0.00,
            'tax_amount' => 16.60,
            'total_amount' => 348.70,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'notes' => 'Please leave at front door.',
        ]);

        // Item 1: GearHub item (Commission: 10%)
        $sub1 = 249.00;
        $comm1 = round($sub1 * 0.10, 2); // $24.90
        $earn1 = round($sub1 - $comm1, 2); // $224.10
        $item1 = OrderItem::create([
            'order_id' => $order1->id,
            'store_id' => $store1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'product_sku' => $p1->sku,
            'product_image' => $p1->primary_image,
            'price' => $p1->price,
            'quantity' => 1,
            'subtotal' => $sub1,
            'commission_rate' => 10.00,
            'admin_commission' => $comm1,
            'vendor_earning' => $earn1,
            'fulfillment_status' => 'delivered',
            'shipping_courier' => 'FedEx Express',
            'tracking_number' => 'FDX-9982348123',
        ]);

        // Item 2: DecorCraft item (Commission: 12%)
        $sub2 = 120.00;
        $comm2 = round($sub2 * 0.12, 2); // $14.40
        $earn2 = round($sub2 - $comm2, 2); // $105.60
        $item2 = OrderItem::create([
            'order_id' => $order1->id,
            'store_id' => $store2->id,
            'product_id' => $p5->id,
            'product_name' => $p5->name,
            'product_sku' => $p5->sku,
            'product_image' => $p5->primary_image,
            'price' => $p5->price,
            'quantity' => 1,
            'subtotal' => $sub2,
            'commission_rate' => 12.00,
            'admin_commission' => $comm2,
            'vendor_earning' => $earn2,
            'fulfillment_status' => 'delivered',
            'shipping_courier' => 'UPS Ground',
            'tracking_number' => '1Z9999999999999999',
        ]);

        // Store ledger transactions for Order 1
        StoreTransaction::create([
            'store_id' => $store1->id,
            'order_item_id' => $item1->id,
            'type' => 'earning',
            'amount' => $earn1,
            'balance_before' => 625.40,
            'balance_after' => 849.50,
            'status' => 'completed',
            'notes' => "Net earning for item #{$item1->id} in order #{$order1->order_number}. Platform fee: \${$comm1}",
        ]);

        StoreTransaction::create([
            'store_id' => $store2->id,
            'order_item_id' => $item2->id,
            'type' => 'earning',
            'amount' => $earn2,
            'balance_before' => 314.40,
            'balance_after' => 420.00,
            'status' => 'completed',
            'notes' => "Net earning for item #{$item2->id} in order #{$order1->order_number}. Platform fee: \${$comm2}",
        ]);

        // Order 2: In-Progress Order from Urban Threads
        $order2 = Order::create([
            'order_number' => 'ORD-' . strtoupper(date('Ymd')) . '-419082',
            'user_id' => $customer2->id,
            'customer_name' => 'Alex Rivera',
            'customer_email' => 'alex@example.com',
            'customer_phone' => '+1 (555) 777-8888',
            'shipping_address' => '500 Sunset Blvd',
            'shipping_city' => 'Los Angeles',
            'shipping_state' => 'CA',
            'shipping_zip' => '90028',
            'shipping_country' => 'United States',
            'subtotal' => 88.00,
            'discount_amount' => 0.00,
            'shipping_fee' => 9.99,
            'tax_amount' => 4.40,
            'total_amount' => 102.39,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);

        $sub3 = 88.00;
        $comm3 = round($sub3 * 0.08, 2); // $7.04
        $earn3 = round($sub3 - $comm3, 2); // $80.96
        $item3 = OrderItem::create([
            'order_id' => $order2->id,
            'store_id' => $store3->id,
            'product_id' => $p8->id,
            'product_name' => $p8->name,
            'product_sku' => $p8->sku,
            'product_image' => $p8->primary_image,
            'price' => $p8->price,
            'quantity' => 1,
            'subtotal' => $sub3,
            'commission_rate' => 8.00,
            'admin_commission' => $comm3,
            'vendor_earning' => $earn3,
            'fulfillment_status' => 'shipped',
            'shipping_courier' => 'USPS Priority',
            'tracking_number' => '9400111899562849102834',
        ]);

        // 8. Create Customer Reviews
        Review::create([
            'user_id' => $customer1->id,
            'product_id' => $p1->id,
            'order_item_id' => $item1->id,
            'rating' => 5,
            'title' => 'Incredible sound clarity and build quality!',
            'comment' => 'The noise cancellation is astounding and cuts out airplane and coffee shop hum completely. Battery lasts almost an entire week of office work.',
            'is_approved' => true,
        ]);

        Review::create([
            'user_id' => $customer1->id,
            'product_id' => $p5->id,
            'order_item_id' => $item2->id,
            'rating' => 5,
            'title' => 'Stunning warmth in my bedroom',
            'comment' => 'The ceramic texture is gorgeous in person. Packed very securely by DecorCraft Living and arrived in pristine condition.',
            'is_approved' => true,
        ]);
    }
}
