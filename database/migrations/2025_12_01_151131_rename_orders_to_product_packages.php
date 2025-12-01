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
        // Rename orders to product_packages
        Schema::rename('orders', 'product_packages');

        // Rename order_products to product_package_items
        Schema::rename('order_products', 'product_package_items');

        // Update foreign key references in product_package_items
        Schema::table('product_package_items', function (Blueprint $table) {
            $table->dropForeign('order_products_order_id_foreign');
            $table->renameColumn('order_id', 'product_package_id');
            $table->foreign('product_package_id')->references('id')->on('product_packages')->cascadeOnDelete();
        });

        // Update column name in product_packages
        Schema::table('product_packages', function (Blueprint $table) {
            $table->renameColumn('order_id', 'package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse column name in product_packages
        Schema::table('product_packages', function (Blueprint $table) {
            $table->renameColumn('package_id', 'order_id');
        });

        // Reverse foreign key in product_package_items
        Schema::table('product_package_items', function (Blueprint $table) {
            $table->dropForeign(['product_package_id']);
            $table->renameColumn('product_package_id', 'order_id');
            $table->foreign('order_id')->references('id')->on('product_packages')->cascadeOnDelete();
        });

        // Rename tables back
        Schema::rename('product_package_items', 'order_products');
        Schema::rename('product_packages', 'orders');
    }
};
