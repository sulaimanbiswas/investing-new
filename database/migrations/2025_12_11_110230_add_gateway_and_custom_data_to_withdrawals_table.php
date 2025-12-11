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
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->foreignId('gateway_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->json('custom_data')->nullable()->after('currency');
            $table->string('wallet_address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign(['gateway_id']);
            $table->dropColumn(['gateway_id', 'custom_data']);
        });
    }
};
