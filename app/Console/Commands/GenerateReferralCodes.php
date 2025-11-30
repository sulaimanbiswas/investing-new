<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for existing users who don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersWithoutCode = User::whereNull('referral_code')->get();

        if ($usersWithoutCode->isEmpty()) {
            $this->info('All users already have referral codes.');
            return 0;
        }

        $this->info("Generating referral codes for {$usersWithoutCode->count()} users...");

        $bar = $this->output->createProgressBar($usersWithoutCode->count());
        $bar->start();

        foreach ($usersWithoutCode as $user) {
            $user->referral_code = User::generateReferralCode();
            $user->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Referral codes generated successfully!');

        return 0;
    }
}
