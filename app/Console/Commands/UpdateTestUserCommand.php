<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateTestUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-password {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a user password to plaintext';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->error("User '{$username}' not found!");
            return;
        }

        $user->update(['password' => $password]);

        $this->info("✓ Password for '{$username}' updated to plaintext: '{$password}'");
        $this->line("You can now login with:");
        $this->line("  Username: {$username}");
        $this->line("  Password: {$password}");
    }
}
