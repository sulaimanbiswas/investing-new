<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class SetFrontendLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Keep admin panel unchanged.
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        $supportedLocales = array_keys(config('localization.supported_locales', ['en' => 'English']));
        $defaultLocale = config('localization.default_locale', config('app.locale', 'en'));

        $queryLocale = $request->query('lang');
        if ($queryLocale) {
            $normalized = $this->normalizeLocale($queryLocale);
            if (in_array($normalized, $supportedLocales, true)) {
                session(['locale' => $normalized]);
            }
        }

        $locale = session('locale');

        if (!$locale) {
            $locale = $request->cookie('locale');
        }

        if (!$locale) {
            $locale = $this->resolveFromCountry($request, $supportedLocales, $defaultLocale);
        }

        $locale = $this->normalizeLocale((string) $locale);
        if (!in_array($locale, $supportedLocales, true)) {
            $locale = $defaultLocale;
        }

        app()->setLocale($locale);

        /** @var Response $response */
        $response = $next($request);
        $response->headers->setCookie(cookie('locale', $locale, 60 * 24 * 180));

        return $response;
    }

    private function normalizeLocale(string $locale): string
    {
        $locale = str_replace('_', '-', trim($locale));

        if ($locale === 'zh') {
            return 'zh-CN';
        }

        if (strtolower($locale) === 'pt-br') {
            return 'pt-BR';
        }

        return $locale;
    }

    private function resolveFromCountry(Request $request, array $supportedLocales, string $defaultLocale): string
    {
        $countryCode = $this->resolveCountryCodeByIp($request);
        if (!$countryCode) {
            return '';
        }

        $countryToLocale = config('localization.country_locale_map', []);
        $locale = $countryToLocale[$countryCode] ?? $defaultLocale;
        $locale = $this->normalizeLocale((string) $locale);

        if (!in_array($locale, $supportedLocales, true)) {
            return $defaultLocale;
        }

        return $locale;
    }

    private function resolveCountryCodeByIp(Request $request): ?string
    {
        $ipAddress = $this->getIpAddress($request);
        if ($ipAddress === '') {
            return null;
        }

        if (in_array($ipAddress, ['127.0.0.1', '::1', '0.0.0.0', 'localhost'], true)) {
            return null;
        }

        $cacheKey = 'geo_country_code_' . md5($ipAddress);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ipAddress) {
            try {
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ipAddress}", [
                    'fields' => 'status,countryCode',
                ]);

                if (!$response->successful() || $response->json('status') !== 'success') {
                    return null;
                }

                $countryCode = strtoupper((string) $response->json('countryCode', ''));

                return $countryCode !== '' ? $countryCode : null;
            } catch (\Throwable) {
                return null;
            }
        });
    }

    private function getIpAddress(Request $request): string
    {
        if ($request->header('CF-Connecting-IP')) {
            return (string) $request->header('CF-Connecting-IP');
        }

        if ($request->header('X-Real-IP')) {
            return (string) $request->header('X-Real-IP');
        }

        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', (string) $request->header('X-Forwarded-For'));
            return trim((string) ($ips[0] ?? ''));
        }

        return (string) ($request->ip() ?? '');
    }
}
