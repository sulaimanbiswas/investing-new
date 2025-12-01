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
        // First update all existing 'completed' records to 'approved'
        DB::table('deposits')
            ->where('status', 'completed')
            ->update(['status' => 'approved']);

        // Then alter the enum to replace 'completed' with 'approved'
        DB::statement("ALTER TABLE deposits MODIFY COLUMN status ENUM('initialed', 'pending', 'approved', 'rejected') DEFAULT 'initialed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update all 'approved' records back to 'completed'
        DB::table('deposits')
            ->where('status', 'approved')
            ->update(['status' => 'completed']);

        // Then alter the enum back to use 'completed'
        DB::statement("ALTER TABLE deposits MODIFY COLUMN status ENUM('initialed', 'pending', 'completed', 'rejected') DEFAULT 'initialed'");
    }
};
