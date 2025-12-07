<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::getValue($key, $default);
    }
}

if (!function_exists('app_title')) {
    /**
     * Get the application title
     */
    function app_title()
    {
        return setting('site_title', config('app.name'));
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get the currency symbol
     */
    function currency_symbol()
    {
        return setting('currency_symbol', '$');
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get the currency code
     */
    function currency_code()
    {
        return setting('currency', 'USDT');
    }
}

if (!function_exists('app_timezone')) {
    /**
     * Get the application timezone
     */
    function app_timezone()
    {
        return setting('timezone', config('app.timezone'));
    }
}

if (!function_exists('daily_order_limit')) {
    /**
     * Get the daily order limit for users
     */
    function daily_order_limit()
    {
        return (int) setting('daily_order_limit', 25);
    }
}

if (!function_exists('captcha_enabled')) {
    /**
     * Check if CAPTCHA is enabled
     */
    function captcha_enabled()
    {
        return (bool) setting('enable_captcha', false);
    }
}
