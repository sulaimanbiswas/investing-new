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
        Schema::table('order_sets', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->enum('type', ['single', 'combo'])->after('id');
            $table->string('order_id')->unique()->after('type');
            $table->decimal('profit_percentage', 8, 2)->after('platform_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_sets', function (Blueprint $table) {
            $table->dropColumn(['type', 'order_id', 'profit_percentage']);
            $table->string('name')->after('id');
        });
    }
};
