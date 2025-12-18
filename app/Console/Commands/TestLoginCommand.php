<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestLoginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login with username and password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        $this->info("========== TESTING LOGIN ==========");
        $this->line("Username: {$username}");
        $this->line("Password: {$password}");

        // Check user exists
        $user = User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found!");
            return;
        }

        $this->line("\n--- User Details ---");
        $this->line("ID: {$user->id}");
        $this->line("Username: {$user->username}");
        $this->line("Email: {$user->email}");
        $this->line("Phone: {$user->phone}");
        $this->line("Password (stored): " . substr($user->password, 0, 20) . "...");
        $this->line("is_admin: " . ($user->is_admin ? 'YES (1)' : 'NO (0)'));
        $this->line("Status: {$user->status}");

        // Test attempt
        $this->line("\n--- Testing Auth::attempt() ---");
        $credentials = [
            'username' => $username,
            'password' => $password
        ];

        $result = Auth::attempt($credentials);

        if ($result) {
            $this->info("✓ Auth::attempt() SUCCEEDED");
            $authUser = Auth::user();
            $this->line("Authenticated as: {$authUser->username}");
            $this->line("is_admin: " . ($authUser->is_admin ? 'YES' : 'NO'));

            // Check if admin blocker would kick in
            if ($authUser->is_admin) {
                $this->warn("⚠ User is admin - would be rejected by user panel");
            } else {
                $this->info("✓ User is not admin - can login");
            }

            Auth::logout();
        } else {
            $this->error("✗ Auth::attempt() FAILED");
        }

        $this->line("\n===============================");
    }
}
