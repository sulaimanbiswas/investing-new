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
        Schema::create('user_order_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_set_id')->constrained()->onDelete('cascade');
            $table->integer('total_products')->default(0); // Total products in this order set
            $table->integer('completed_products')->default(0); // Completed products count
            $table->decimal('total_amount', 12, 2)->default(0); // Total order amount
            $table->decimal('profit_amount', 12, 2)->default(0); // Total profit earned
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'order_set_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_order_sets');
    }
};
