<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class LoginTrackingService
{
    protected Agent $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Track user login with detailed information.
     */
    public function trackLogin(User $user, Request $request, string $status = 'success', ?string $failureReason = null): LoginHistory
    {
        $ipAddress = $this->getIpAddress($request);
        $locationData = $this->getLocationData($ipAddress);
        $deviceData = $this->getDeviceData($request);

        return LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,

            // Location data
            'country' => $locationData['country'] ?? null,
            'country_code' => $locationData['country_code'] ?? null,
            'region' => $locationData['region'] ?? null,
            'region_code' => $locationData['region_code'] ?? null,
            'city' => $locationData['city'] ?? null,
            'zip_code' => $locationData['zip_code'] ?? null,
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
            'timezone' => $locationData['timezone'] ?? null,
            'isp' => $locationData['isp'] ?? null,

            // Device data
            'user_agent' => $deviceData['user_agent'],
            'browser' => $deviceData['browser'],
            'browser_version' => $deviceData['browser_version'],
            'platform' => $deviceData['platform'],
            'platform_version' => $deviceData['platform_version'],
            'device' => $deviceData['device'],
            'device_model' => $deviceData['device_model'],

            // Status
            'status' => $status,
            'failure_reason' => $failureReason,
        ]);
    }

    /**
     * Get real IP address from request.
     */
    protected function getIpAddress(Request $request): string
    {
        // Check for IP behind proxy
        if ($request->header('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP'); // Cloudflare
        }

        if ($request->header('X-Real-IP')) {
            return $request->header('X-Real-IP');
        }

        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            return trim($ips[0]);
        }

        return $request->ip() ?? '0.0.0.0';
    }

    /**
     * Get location data from IP using free IP geolocation API.
     */
    protected function getLocationData(string $ipAddress): array
    {
        // Skip for local IPs
        if (in_array($ipAddress, ['127.0.0.1', '::1', '0.0.0.0', 'localhost'])) {
            return [
                'country' => 'Local',
                'country_code' => 'LC',
                'region' => 'Local',
                'region_code' => 'LC',
                'city' => 'Localhost',
                'zip_code' => null,
                'latitude' => null,
                'longitude' => null,
                'timezone' => config('app.timezone'),
                'isp' => 'Local Network',
            ];
        }

        // Skip geolocation if CURL is not properly configured
        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            Log::warning('CURL SSL constants not available, skipping geolocation', [
                'ip' => $ipAddress,
            ]);
            return [];
        }

        try {
            // Using ip-api.com (free, no API key needed, 45 req/min limit)
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ipAddress}", [
                'fields' => 'status,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp',
            ]);

            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json();
                return [
                    'country' => $data['country'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'region' => $data['regionName'] ?? null,
                    'region_code' => $data['region'] ?? null,
                    'city' => $data['city'] ?? null,
                    'zip_code' => $data['zip'] ?? null,
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'isp' => $data['isp'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            // Log error silently, don't break login flow
            Log::warning('Failed to fetch location data', [
                'ip' => $ipAddress,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Get device and browser data from user agent.
     */
    protected function getDeviceData(Request $request): array
    {
        $this->agent->setUserAgent($request->userAgent() ?? '');

        // Determine device type
        $device = 'Desktop';
        if ($this->agent->isPhone()) {
            $device = 'Mobile';
        } elseif ($this->agent->isTablet()) {
            $device = 'Tablet';
        } elseif ($this->agent->isRobot()) {
            $device = 'Bot';
        }

        return [
            'user_agent' => $request->userAgent() ?? 'Unknown',
            'browser' => $this->agent->browser() ?? 'Unknown',
            'browser_version' => $this->agent->version($this->agent->browser()) ?? null,
            'platform' => $this->agent->platform() ?? 'Unknown',
            'platform_version' => $this->agent->version($this->agent->platform()) ?? null,
            'device' => $device,
            'device_model' => $this->agent->device() ?? null,
        ];
    }

    /**
     * Get recent login history for a user.
     */
    public function getRecentLogins(User $user, int $limit = 10)
    {
        return $user->loginHistories()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Check for suspicious login activity.
     */
    public function isSuspiciousLogin(User $user, Request $request): bool
    {
        $ipAddress = $this->getIpAddress($request);

        // Get last successful login
        $lastLogin = $user->loginHistories()
            ->where('status', 'success')
            ->latest()
            ->first();

        if (!$lastLogin) {
            return false; // First login, can't be suspicious
        }

        // Check if IP changed and location changed significantly
        if ($lastLogin->ip_address !== $ipAddress) {
            // Check if location changed (different country)
            $currentLocation = $this->getLocationData($ipAddress);

            if (
                isset($currentLocation['country_code']) &&
                $lastLogin->country_code &&
                $currentLocation['country_code'] !== $lastLogin->country_code
            ) {
                return true;
            }
        }

        // Check for rapid login attempts (more than 5 in last hour)
        $recentAttempts = $user->loginHistories()
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentAttempts > 5) {
            return true;
        }

        return false;
    }
}
