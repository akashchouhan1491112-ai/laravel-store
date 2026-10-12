# Multi-Vendor E-Commerce Marketplace (Laravel 12 + MySQL)

An enterprise-grade, full-featured **Multi-Vendor E-Commerce Marketplace** built with **Laravel 12**, **MySQL / SQLite**, **Tailwind CSS**, **Alpine.js**, and **Laravel Sanctum**.

---

## 🌟 Key Capabilities & Features

### 1. Multi-Role Authentication & Access Control (RBAC)
- **Roles**: `admin`, `vendor`, `customer`.
- **Session & API Guards**: Stateful web sessions + Sanctum Bearer API tokens.
- **Role Middleware & Policies**:
  - `RoleMiddleware`: Enforces role-based route protection (`role:admin`, `role:vendor,admin`).
  - `VendorApprovedMiddleware`: Ensures vendors can only access product management, orders, and payouts once approved by an Admin.
  - Granular Eloquent Policies: `ProductPolicy`, `StorePolicy`, `OrderPolicy`, `ReviewPolicy`.
- **1-Click Demo Fast Switcher Bar**: Sticky header bar allowing instant switching between Admin, Vendor, Customer, or Guest roles without typing credentials.

### 2. Multi-Vendor Shopping Cart & Line-Item Splitting
- **Vendor-Grouped Cart**: Items in the cart are organized by Vendor Store with individual vendor subtotals and platform totals.
- **Coupons Engine**: Platform-wide and store-specific percentage / fixed discount coupons with minimum spend thresholds.
- **Guest-to-User Transition**: Preserves cart items added as guest upon registration or login.

### 3. Order Processing & Financial Ledger Architecture
- **Multi-Vendor Checkout (`OrderService`)**:
  - Encapsulated inside `DB::transaction()` to guarantee atomic state changes.
  - Automatically splits order line items across distinct vendors (`OrderItem`).
  - **Dynamic Commission Deduction**: Calculates platform commission and vendor net earnings (`admin_commission = subtotal * (rate / 100)`, `vendor_earning = subtotal - admin_commission`).
  - **Double-Entry Store Ledger (`StoreTransaction`)**: Credits vendor balances with audit trail records (`type: sale_earning`, `type: commission_fee`, `type: payout_withdrawal`, `type: refund_reversal`).
  - **Inventory Decrements**: Deducts stock quantities per product automatically.

### 4. Vendor Store Management & Fulfillment
- **Storefronts**: Custom store banners, logos, biographies, ratings, and featured products.
- **Product Catalog Management**: Multi-image galleries, price comparisons, SKU tracking, stock management, categories, and published/draft statuses.
- **Per-Vendor Line-Item Fulfillment**: Vendors only see and manage their own order items. They can update statuses (`pending` ➔ `processing` ➔ `shipped` ➔ `delivered`), assign shipping couriers, and provide tracking numbers.
- **Payout & Withdrawal System (`VendorPayoutService`)**:
  - Vendors submit withdrawal requests against their available store balance.
  - Available balance is locked pending Admin review.
  - Admin approval marks payouts complete, or rejection restores vendor balances with audit logs.

### 5. Verified Customer Reviews & Reputation
- Only customers with a confirmed purchase of the product can submit 1–5 star ratings and reviews.
- Automatically calculates and updates the product's average rating and reviews count.

### 6. Admin Control Center
- Real-time marketplace statistics: Gross Merchandise Value (GMV), Platform Commissions Earned, Total Orders, Active Vendors, and Pending Approvals.
- Vendor KYC/Approval queue (Approve, Suspend, Reject).
- Global Category hierarchy management with icons and parent-child nesting.
- Global Order oversight with administrative cancellation and inventory rollback.
- Payout approval workflow.

---

## ⚡ Seeded Demo Accounts

| Role | Email | Password | Details |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@marketplace.com` | `password123` | Platform Administrator with full access |
| **Vendor 1 (Approved)** | `gearhub@marketplace.com` | `password123` | *GearHub Technologies* (Electronics, 10% fee) |
| **Vendor 2 (Approved)** | `decorcraft@marketplace.com` | `password123` | *DecorCraft Living* (Home & Living, 12% fee) |
| **Vendor 3 (Approved)** | `urban@marketplace.com` | `password123` | *Urban Threads Co.* (Fashion, 15% fee) |
| **Vendor 4 (Pending)** | `nextgen@marketplace.com` | `password123` | *Summit Trail & Mountain Gear* (Awaiting admin approval) |
| **Customer 1** | `sarah@example.com` | `password123` | Active customer with previous orders & reviews |
| **Customer 2** | `alex@example.com` | `password123` | Customer account |

---

## 🌐 RESTful API Reference (`/api/v1`)

All endpoints support JSON formatting and CORS. Authentication uses `Authorization: Bearer <token>`.

### Public Catalog
- `GET /api/v1/categories` — List active category hierarchy.
- `GET /api/v1/products` — Paginated catalog with search (`q`), category filter (`category`), sort (`sort=price_asc|price_desc|rating`), and vendor filter (`store_id`).
- `GET /api/v1/products/{slug}` — Single product with gallery, vendor details, and reviews.
- `GET /api/v1/stores` — List verified vendor storefronts.
- `GET /api/v1/stores/{slug}` — Single store profile with published products and rating.

### Authentication (`/api/v1/auth`)
- `POST /api/v1/auth/register` — Register customer or vendor (`name`, `email`, `password`, `role`).
- `POST /api/v1/auth/login` — Login and receive Sanctum Bearer token.
- `POST /api/v1/auth/logout` — Revoke active token.

### Shopping Cart (`/api/v1/cart`)
- `GET /api/v1/cart` — Grouped cart items, vendor subtotals, discount, tax, shipping, and grand total.
- `POST /api/v1/cart/add` — Add product (`product_id`, `quantity`).
- `POST /api/v1/cart/update/{id}` — Update item quantity.
- `DELETE /api/v1/cart/remove/{id}` — Remove item.
- `POST /api/v1/cart/coupon/apply` — Apply promo code (`WELCOME10`, `MARKET20`, `FREESHIP`).
- `POST /api/v1/cart/coupon/remove` — Remove applied coupon.

### Checkout & Customer Dashboard (`/api/v1/customer/*`)
- `POST /api/v1/checkout` — Place multi-vendor order (`shipping_name`, `shipping_address`, `shipping_city`, `shipping_postal_code`, `shipping_country`, `shipping_phone`, `payment_method`).
- `GET /api/v1/customer/orders` — Customer purchase history.
- `GET /api/v1/customer/orders/{orderNumber}` — Detailed order summary with vendor tracking numbers.
- `POST /api/v1/customer/reviews` — Submit verified purchase review.

### Vendor API (`/api/v1/vendor/*`)
- `GET /api/v1/vendor/dashboard` — Store metrics (sales, commissions, balance, fulfillment rate).
- `GET /api/v1/vendor/products` — Manage store product inventory.
- `POST /api/v1/vendor/products` — Create new product with images.
- `PUT /api/v1/vendor/products/{id}` — Update product.
- `DELETE /api/v1/vendor/products/{id}` — Remove product.
- `GET /api/v1/vendor/orders` — List assigned vendor order items.
- `POST /api/v1/vendor/orders/{id}/status` — Update shipping status (`shipped`, `delivered`, `shipping_courier`, `tracking_number`).
- `GET /api/v1/vendor/payouts` — Ledger transaction history and pending withdrawals.
- `POST /api/v1/vendor/payouts/request` — Request payout withdrawal.

### Admin API (`/api/v1/admin/*`)
- `GET /api/v1/admin/dashboard` — Platform overview.
- `GET /api/v1/admin/vendors` — List all vendors with status filter (`status=pending|approved|suspended`).
- `POST /api/v1/admin/vendors/{store}/status` — Approve, suspend, or reject vendor.
- `GET /api/v1/admin/orders` — Global orders list.
- `POST /api/v1/admin/payouts/{id}/approve` — Approve vendor withdrawal.
- `POST /api/v1/admin/payouts/{id}/reject` — Reject vendor withdrawal.

---

## 🛠️ Testing & Validation

Run the automated test suite anytime with:
```powershell
php artisan test
```
All 8 feature & unit tests validate catalog browsing, multi-vendor cart grouping, checkout transaction atomicity, commission calculations, vendor order fulfillment, and payout lifecycles.
