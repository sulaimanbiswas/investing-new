<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(setting('favicon_path'))
        <link rel="icon" type="image/x-icon" href="{{ asset(setting('favicon_path')) }}">
    @else
        <link rel="icon" type="image/svg+xml"
            href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75' font-weight='bold' fill='%2336C95F'>$</text></svg>">
    @endif

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [dir="rtl"] .auth-card .left-4 {
            left: auto;
            right: 1rem;
        }

        [dir="rtl"] .auth-card .right-4 {
            right: auto;
            left: 1rem;
        }

        [dir="rtl"] .auth-card .pl-12 {
            padding-left: 1rem;
            padding-right: 3rem;
        }

        [dir="rtl"] .auth-card .pr-12 {
            padding-right: 1rem;
            padding-left: 3rem;
        }

        [dir="rtl"] .auth-card .ml-1 {
            margin-left: 0;
            margin-right: 0.25rem;
        }

        [dir="rtl"] .auth-card .mr-1 {
            margin-right: 0;
            margin-left: 0.25rem;
        }

        [dir="rtl"] .auth-card .border-l-4 {
            border-left-width: 0;
            border-right-width: 4px;
        }
    </style>

    @stack('styles')
</head>

<body
    class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center p-4 md:p-6">
 @php
            $localeCountries = [
                'en' => 'us',
                'es' => 'es',
                'pt' => 'pt',
                'pt-br' => 'br',
                'ru' => 'ru',
                'ko' => 'kr',
                'ja' => 'jp',
                'nl' => 'nl',
                'el' => 'gr',
                'de' => 'de',
                'bn' => 'bd',
                'ar' => 'sa',
                'tr' => 'tr',
                'zh-cn' => 'cn',
                'hi' => 'in',
                'ro' => 'ro',
                'ur' => 'pk',
            ];

            $localeNames = [
                'en' => 'English',
                'es' => 'Español',
                'pt' => 'Português',
                'pt-br' => 'Português (Brasil)',
                'ru' => 'Русский',
                'ko' => '한국어',
                'ja' => '日本語',
                'nl' => 'Nederlands',
                'el' => 'Ελληνικά',
                'de' => 'Deutsch',
                'bn' => 'বাংলা',
                'ar' => 'العربية',
                'tr' => 'Türkçe',
                'zh-cn' => '中文(简体)',
                'hi' => 'हिन्दी',
                'ro' => 'Română',
                'ur' => 'اردو',
            ];

            $currentLocale = app()->getLocale();
            $normalizedCurrentLocale = strtolower(str_replace('_', '-', $currentLocale));
            $currentCountryCode = $localeCountries[$normalizedCurrentLocale] ?? 'us';
            $currentLocaleName = $localeNames[$normalizedCurrentLocale] ?? strtoupper($currentLocale);
        @endphp

        <div class="mb-6 flex items-center justify-end absolute top-4 right-4">
            <div class="rounded-2xl border border-gray-100 bg-gray-50/80 p-1 shadow-sm">
                <form method="POST" action="{{ route('locale.switch', app()->getLocale()) }}" 
                    x-data="{ open: false }" @click.away="open = false">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $currentLocale }}" x-ref="localeInput">
                    <div class="relative">
                        <button type="button" @click="open = !open"
                            class="h-10 min-w-[126px] rounded-xl border border-gray-200 bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm transition hover:border-indigo-300 focus:border-indigo-500 focus:outline-none inline-flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-2">
                                <img src="https://flagcdn.com/20x15/{{ $currentCountryCode }}.png"
                                    srcset="https://flagcdn.com/40x30/{{ $currentCountryCode }}.png 2x"
                                    alt="{{ strtoupper($currentLocale) }}" class="h-[15px] w-5 rounded-sm object-cover">
                                <span>{{ $currentLocaleName }}</span>
                            </span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                        </button>

                        <div x-show="open" x-transition x-cloak style="display:none"
                            class="absolute right-0 mt-2 max-h-72 w-44 overflow-auto rounded-xl border border-gray-200 bg-white p-1 shadow-xl z-50">
                            @foreach(($supportedLocales ?? []) as $code => $label)
                                @php
                                    $normalizedCode = strtolower(str_replace('_', '-', $code));
                                    $countryCode = $localeCountries[$normalizedCode] ?? 'us';
                                    $localeName = $localeNames[$normalizedCode] ?? $label;
                                @endphp
                                <button type="button"
                                    @click="$refs.localeInput.value='{{ $code }}'; $el.closest('form').action='{{ url('/locale') }}/{{ $code }}'; $el.closest('form').submit();"
                                    class="w-full rounded-lg px-3 py-2 text-left text-xs font-medium transition hover:bg-indigo-50 {{ $currentLocale === $code ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }} inline-flex items-center gap-2">
                                    <img src="https://flagcdn.com/20x15/{{ $countryCode }}.png"
                                        srcset="https://flagcdn.com/40x30/{{ $countryCode }}.png 2x" alt="{{ strtoupper($code) }}"
                                        class="h-[15px] w-5 rounded-sm object-cover">
                                    <span>{{ $localeName }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <div class="auth-card bg-white rounded-3xl shadow-2xl p-6 md:p-10 w-full max-w-sm md:max-w-md">



        @yield('content')
    </div>

    @yield('scripts')
    @stack('scripts')

    <!-- PHP Flasher -->
    @flasher_render
</body>

</html>