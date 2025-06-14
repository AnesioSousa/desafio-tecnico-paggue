<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign key to orders
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Foreign key to batches (the missing column)
            $table->foreignId('batch_id')
                ->constrained('batches')
                ->cascadeOnDelete();

            // Quantity ordered
            $table->unsignedInteger('quantity');

            // Applied coupon (nullable)
            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained('coupons')
                ->nullOnDelete();

            // Unit price and discount value
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_value', 10, 2)->default(0);

            $table->timestamps();
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
