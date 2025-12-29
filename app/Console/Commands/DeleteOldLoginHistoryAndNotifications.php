<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginHistory;
use App\Models\Notification;
use Carbon\Carbon;

class DeleteOldLoginHistoryAndNotifications extends Command
{
    protected $signature = 'cleanup:old-login-notifications';
    protected $description = 'Delete login history and notifications older than 15 days';

    public function handle()
    {
        $cutoff = Carbon::now()->subDays(15);

        $loginCount = LoginHistory::where('created_at', '<', $cutoff)->delete();
        $notificationCount = Notification::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted $loginCount old login history records.");
        $this->info("Deleted $notificationCount old notifications.");
    }
}
