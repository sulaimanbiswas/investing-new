<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add phone field if it doesn't exist
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                // Add phone field after username (nullable first)
                $table->string('phone', 20)->nullable()->after('username');
            });
        }

        // Update existing users with temporary unique phone numbers
        $users = DB::table('users')->whereNull('phone')->orWhere('phone', '')->get();
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['phone' => '+' . $user->id . time()]);
        }

        // Now make phone unique and not nullable, and make email nullable
        // Check if unique index exists
        $indexExists = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_phone_unique'");

        Schema::table('users', function (Blueprint $table) use ($indexExists) {
            // Drop existing index if exists
            if (!empty($indexExists)) {
                $table->dropUnique('users_phone_unique');
            }

            $table->string('phone', 20)->unique()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('email')->nullable(false)->change();
        });
    }
};
