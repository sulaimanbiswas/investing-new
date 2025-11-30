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
        // First, restore order_sets to simple structure (like categories)
        Schema::table('order_sets', function (Blueprint $table) {
            $table->dropColumn(['type', 'order_id', 'profit_percentage']);
            $table->string('name')->after('id');
        });

        // Create orders table (the actual orders that belong to order sets)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_set_id')->constrained('order_sets')->cascadeOnDelete();
            $table->enum('type', ['single', 'combo']);
            $table->string('order_id')->unique();
            $table->foreignId('platform_id')->constrained('platforms')->cascadeOnDelete();
            $table->decimal('profit_percentage', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update order_products to reference orders instead of order_sets
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropForeign(['order_set_id']);
            $table->dropColumn('order_set_id');
            $table->foreignId('order_id')->after('id')->constrained('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
            $table->foreignId('order_set_id')->after('id')->constrained('order_sets')->cascadeOnDelete();
        });

        Schema::dropIfExists('orders');

        Schema::table('order_sets', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->enum('type', ['single', 'combo'])->after('id');
            $table->string('order_id')->unique()->after('type');
            $table->decimal('profit_percentage', 8, 2)->after('platform_id');
        });
    }
};
