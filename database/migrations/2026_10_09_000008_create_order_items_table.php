<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('admin_commission', 10, 2)->default(0.00);
            $table->decimal('vendor_earning', 10, 2)->default(0.00);
            $table->enum('fulfillment_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('processing')->index();
            $table->string('tracking_number')->nullable();
            $table->string('shipping_courier')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'fulfillment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
