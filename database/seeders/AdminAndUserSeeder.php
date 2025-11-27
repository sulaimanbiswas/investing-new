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
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => 'password', // hashed via casts
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Normal user account
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'username' => 'user',
                'password' => 'password', // hashed via casts
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
