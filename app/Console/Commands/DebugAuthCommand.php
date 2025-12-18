<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DebugAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug authentication issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========== CHECKING DATABASE USERS ==========');

        $users = User::where('is_admin', false)->select('id', 'username', 'phone', 'password')->limit(5)->get();

        if ($users->isEmpty()) {
            $this->error('NO USERS FOUND IN DATABASE!');
            return;
        }

        foreach ($users as $user) {
            $this->line("\n--- User: {$user->username} ---");
            $this->line("ID: {$user->id}");
            $this->line("Phone: {$user->phone}");
            $passwordPreview = substr($user->password, 0, 30) . (strlen($user->password) > 30 ? "..." : "");
            $this->line("Password (preview): {$passwordPreview}");

            $isBcrypt = strpos($user->password, '$2') === 0;
            $this->line("Is Bcrypt: " . ($isBcrypt ? "YES" : "NO"));

            // Test plaintext
            $this->line("\nTesting plaintext 'password':");
            if ('password' === $user->password) {
                $this->info("  ✓ PLAINTEXT MATCH!");
            } else {
                $this->warn("  ✗ No plaintext match");
            }

            // Test plaintext with stored password
            $this->line("Testing plaintext with actual stored password:");
            if ($user->password === $user->password) {
                $this->info("  ✓ PLAINTEXT MATCH WITH STORED!");
            }

            // Test hash
            if ($isBcrypt) {
                $this->line("Testing bcrypt 'password':");
                if (Hash::check('password', $user->password)) {
                    $this->info("  ✓ BCRYPT MATCH!");
                } else {
                    $this->warn("  ✗ No bcrypt match");
                }
            }
        }

        $this->line("\n========== END DEBUG ==========");
    }
}
