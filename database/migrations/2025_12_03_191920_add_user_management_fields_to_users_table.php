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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('daily_order_limit')->default(25)->after('balance');
            $table->decimal('freeze_amount', 15, 2)->default(0)->after('daily_order_limit');
            $table->string('withdrawal_address')->nullable()->after('freeze_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_order_limit', 'freeze_amount', 'withdrawal_address']);
        });
    }
};
