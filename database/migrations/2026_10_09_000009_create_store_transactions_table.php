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
        Schema::create('store_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['earning', 'commission_deduction', 'payout_withdrawal', 'refund'])->index();
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2)->default(0.00);
            $table->decimal('balance_after', 12, 2)->default(0.00);
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('completed')->index();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_transactions');
    }
};
