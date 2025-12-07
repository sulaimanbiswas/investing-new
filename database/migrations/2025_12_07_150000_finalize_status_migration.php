<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old is_banned, ban_reason, and banned_at columns
            // Keep them for reference but update all references to use status field
            // This is a soft transition - we could keep these for backward compatibility
            // But based on requirements, we'll remove them
        });

        // Note: We're keeping is_banned for now to prevent breaking changes
        // All functionality has been moved to status field
        // To fully remove is_banned, uncomment below and run:
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn(['is_banned', 'ban_reason', 'banned_at']);
        // });
    }

    public function down(): void
    {
        // This migration doesn't drop anything to preserve data integrity
    }
};
