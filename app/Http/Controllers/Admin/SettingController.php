<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        // Available currencies and timezones
        $currencies = ['USDT', 'USD', 'EUR', 'GBP', 'INR', 'BDT'];
        $timezones = timezone_identifiers_list();

        // Get current values or defaults
        $data = [
            'site_title' => $settings->get('site_title')?->value ?? 'Lavanta Mall',
            'currency' => $settings->get('currency')?->value ?? 'USDT',
            'currency_symbol' => $settings->get('currency_symbol')?->value ?? 'USDT',
            'timezone' => $settings->get('timezone')?->value ?? 'Europe/London',
            'daily_order_limit' => $settings->get('daily_order_limit')?->value ?? 25,
            'enable_captcha' => $settings->get('enable_captcha')?->value ?? true,
            'telegram_support_link' => $settings->get('telegram_support_link')?->value ?? '',
            'whatsapp_support_link' => $settings->get('whatsapp_support_link')?->value ?? '',
            'notify_heading' => $settings->get('notify_heading')?->value ?? '',
            'notify_text' => $settings->get('notify_text')?->value ?? '',
            'help_description' => $settings->get('help_description')?->value ?? '',
            'help_email' => $settings->get('help_email')?->value ?? '',
            'help_link' => $settings->get('help_link')?->value ?? '',
            'logo_path' => $settings->get('logo_path')?->value ?? '',
            'favicon_path' => $settings->get('favicon_path')?->value ?? '',
        ];

        return view('admin.settings.general', compact('data', 'currencies', 'timezones'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_title' => 'required|string|max:255',
            'currency' => 'required|string',
            'currency_symbol' => 'required|string|max:10',
            'timezone' => 'required|string|timezone',
            'daily_order_limit' => 'required|integer|min:1',
            'enable_captcha' => 'boolean',
            'telegram_support_link' => 'nullable|url',
            'whatsapp_support_link' => 'nullable|url',
            'notify_heading' => 'nullable|string|max:255',
            'notify_text' => 'nullable|string',
            'help_description' => 'nullable|string',
            'help_email' => 'nullable|email',
            'help_link' => 'nullable|url',
            'logo_path' => 'nullable|string',
            'favicon_path' => 'nullable|string',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            $type = match ($key) {
                'enable_captcha' => 'boolean',
                'daily_order_limit' => 'integer',
                default => 'string',
            };

            Setting::setValue($key, $value, $type);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function seo(): View
    {
        $settings = Setting::all()->keyBy('key');

        $data = [
            'og_image_path' => $settings->get('og_image_path')?->value ?? '',
            'meta_description' => $settings->get('meta_description')?->value ?? '',
            'meta_keywords' => $settings->get('meta_keywords')?->value ?? '',
            'social_title' => $settings->get('social_title')?->value ?? '',
            'social_description' => $settings->get('social_description')?->value ?? '',
        ];

        return view('admin.settings.seo', compact('data'));
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'og_image_path' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'social_title' => 'nullable|string|max:255',
            'social_description' => 'nullable|string|max:200',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value, 'string');
        }

        return redirect()->route('admin.settings.seo')
            ->with('success', 'SEO settings updated successfully!');
    }
}
