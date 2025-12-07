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
        Schema::create('user_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_order_set_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('product_package_item_id')->nullable();
            $table->string('order_number')->unique();
            $table->enum('type', ['normal', 'combo'])->default('normal');
            $table->string('product_name'); // Cached product name
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('order_amount', 12, 2); // quantity * price
            $table->decimal('profit_amount', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2); // User balance after this order
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->json('manage_crypto')->nullable(); // Product details for expand view
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_orders');
    }
};
