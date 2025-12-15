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

if (!function_exists('optimize_image_path')) {
    /**
     * Create and return a compressed thumbnail URL for a public image path.
     * Stores optimized images under an 'optimized' subfolder next to original.
     */
    function optimize_image_path(?string $path, int $maxWidth = 600, int $quality = 70): ?string
    {
        if (!$path) {
            return null;
        }

        $publicPath = public_path();
        $normalized = ltrim($path, '/\\');
        $fullPath = $publicPath . DIRECTORY_SEPARATOR . $normalized;

        if (!file_exists($fullPath)) {
            return $path; // return original if missing
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $basename = pathinfo($fullPath, PATHINFO_FILENAME);
        $dir = pathinfo($fullPath, PATHINFO_DIRNAME);

        $optimizedDir = $dir . DIRECTORY_SEPARATOR . 'optimized';
        if (!is_dir($optimizedDir)) {
            @mkdir($optimizedDir, 0755, true);
        }

        $targetName = $basename . "_w{$maxWidth}.jpg";
        $targetPath = $optimizedDir . DIRECTORY_SEPARATOR . $targetName;

        // Already optimized
        if (file_exists($targetPath)) {
            $relative = str_replace($publicPath . DIRECTORY_SEPARATOR, '', $targetPath);
            return '/' . str_replace('\\', '/', $relative);
        }

        // Load image using GD
        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                $image = @imagecreatefromjpeg($fullPath);
                break;
            case 'png':
                $image = @imagecreatefrompng($fullPath);
                break;
            case 'gif':
                $image = @imagecreatefromgif($fullPath);
                break;
            case 'webp':
                $image = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : false;
                break;
            default:
                $image = false;
        }

        if (!$image) {
            return $path; // fallback
        }

        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        if ($origWidth <= $maxWidth) {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        } else {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * $ratio);
        }

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        // Fill background white for formats with transparency
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        @imagejpeg($canvas, $targetPath, max(10, min(90, $quality)));

        imagedestroy($canvas);
        imagedestroy($image);

        if (file_exists($targetPath)) {
            $relative = str_replace($publicPath . DIRECTORY_SEPARATOR, '', $targetPath);
            return '/' . str_replace('\\', '/', $relative);
        }

        return $path;
    }
}
