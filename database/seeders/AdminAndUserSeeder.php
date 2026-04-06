<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'phone' => '+1234567890',
                'email' => 'admin@example.com',
                'password' => 'password',
                'withdrawal_password' => 'password',
                'is_admin' => true,
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Normal user account
        DB::table('users')->updateOrInsert(
            ['username' => 'user'],
            [
                'name' => 'User',
                'phone' => '+1234567891',
                'email' => 'user@example.com',
                'password' => 'password',
                'withdrawal_password' => 'password',
                'is_admin' => false,
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
