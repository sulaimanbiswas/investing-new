<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- SEO Meta Tags -->
    @if(setting('meta_description'))
        <meta name="description" content="{{ setting('meta_description') }}">
    @endif
    @if(setting('meta_keywords'))
        <meta name="keywords" content="{{ setting('meta_keywords') }}">
    @endif

    <!-- Open Graph Tags -->
    <meta property="og:title"
        content="{{ setting('social_title') ?: setting('site_title') ?: config('app.name', 'Laravel') }}">
    @if(setting('social_description'))
        <meta property="og:description" content="{{ setting('social_description') }}">
    @elseif(setting('meta_description'))
        <meta property="og:description" content="{{ setting('meta_description') }}">
    @endif
    @if(setting('og_image_path'))
        <meta property="og:image" content="{{ asset(setting('og_image_path')) }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
        content="{{ setting('social_title') ?: setting('site_title') ?: config('app.name', 'Laravel') }}">
    @if(setting('social_description'))
        <meta name="twitter:description" content="{{ setting('social_description') }}">
    @elseif(setting('meta_description'))
        <meta name="twitter:description" content="{{ setting('meta_description') }}">
    @endif
    @if(setting('og_image_path'))
        <meta name="twitter:image" content="{{ asset(setting('og_image_path')) }}">
    @endif

    <!-- Favicon -->
    @if(setting('favicon_path'))
        <link rel="icon" type="image/x-icon" href="{{ asset(setting('favicon_path')) }}">
    @else
        <link rel="icon" type="image/svg+xml"
            href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75' font-weight='bold' fill='%2336C95F'>$</text></svg>">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Back to Admin Button -->
        @if(session()->has('impersonated_by_admin'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3" role="alert">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">You are viewing as user (Impersonated by Admin)</span>
                    </div>
                    <form action="{{ route('return-to-admin') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-1 px-4 rounded transition duration-150 ease-in-out">
                            <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Admin
                        </button>
                    </form>
                </div>
            </div>
        @endif

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- PHP Flasher -->
    @flasher_render

    <!-- Scripts Stack -->
    @stack('scripts')
</body>

</html>