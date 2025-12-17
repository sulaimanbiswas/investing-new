<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'phone' => '+1234567890',
                'email' => 'admin@example.com',
                'password' => 'password', // hashed via casts
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Normal user account
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'User',
                'phone' => '+1234567891',
                'email' => 'user@example.com',
                'password' => 'password', // hashed via casts
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
