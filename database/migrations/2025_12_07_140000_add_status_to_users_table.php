<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add status column with enum values: active, banned
            $table->enum('status', ['active', 'banned'])->default('active')->after('is_banned');
        });

        // Migrate data from is_banned to status
        DB::statement("UPDATE users SET status = 'banned' WHERE is_banned = 1");
        DB::statement("UPDATE users SET status = 'active' WHERE is_banned = 0");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
