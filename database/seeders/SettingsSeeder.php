<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'Investing Platform']);
        Setting::updateOrCreate(['key' => 'app_description'], ['value' => 'A comprehensive investment management platform']);
        Setting::updateOrCreate(['key' => 'currency_symbol'], ['value' => 'USDT']);
        Setting::updateOrCreate(['key' => 'currency_code'], ['value' => 'USDT']);

        // Commission Settings
        Setting::updateOrCreate(['key' => 'default_level1_commission'], ['value' => '5.00']);
        Setting::updateOrCreate(['key' => 'default_level2_commission'], ['value' => '3.00']);
        Setting::updateOrCreate(['key' => 'default_level3_commission'], ['value' => '1.00']);

        // Platform Settings
        Setting::updateOrCreate(['key' => 'maintenance_mode'], ['value' => '0']);
        Setting::updateOrCreate(['key' => 'site_url'], ['value' => 'https://investing.local']);
        Setting::updateOrCreate(['key' => 'support_email'], ['value' => 'support@investing.local']);

        // Deposit Settings
        Setting::updateOrCreate(['key' => 'min_deposit'], ['value' => '10']);
        Setting::updateOrCreate(['key' => 'max_deposit'], ['value' => '50000']);
        Setting::updateOrCreate(['key' => 'deposit_auto_approve'], ['value' => '0']);

        // Withdrawal Settings
        Setting::updateOrCreate(['key' => 'min_withdrawal'], ['value' => '10']);
        Setting::updateOrCreate(['key' => 'max_withdrawal'], ['value' => '50000']);
        Setting::updateOrCreate(['key' => 'withdrawal_auto_approve'], ['value' => '0']);

        // Security Settings
        Setting::updateOrCreate(['key' => 'max_login_attempts'], ['value' => '5']);
        Setting::updateOrCreate(['key' => 'login_attempt_timeout'], ['value' => '15']);
        Setting::updateOrCreate(['key' => 'session_timeout'], ['value' => '480']);

        // Email Settings
        Setting::updateOrCreate(['key' => 'send_welcome_email'], ['value' => '1']);
        Setting::updateOrCreate(['key' => 'send_deposit_notification'], ['value' => '1']);
        Setting::updateOrCreate(['key' => 'send_withdrawal_notification'], ['value' => '1']);
        Setting::updateOrCreate(['key' => 'send_commission_notification'], ['value' => '1']);
    }
}
