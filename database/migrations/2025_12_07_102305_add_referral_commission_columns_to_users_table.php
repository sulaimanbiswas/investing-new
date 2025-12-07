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
            $table->decimal('level1_commission', 8, 2)->default(0)->after('referred_by')->comment('Level 1 referral commission percentage');
            $table->decimal('level2_commission', 8, 2)->default(0)->after('level1_commission')->comment('Level 2 referral commission percentage');
            $table->decimal('level3_commission', 8, 2)->default(0)->after('level2_commission')->comment('Level 3 referral commission percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['level1_commission', 'level2_commission', 'level3_commission']);
        });
    }
};
