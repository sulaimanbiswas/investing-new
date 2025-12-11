<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        /* Responsive success ticker height */
        .successSwiper {
            width: 100%;
            height: clamp(64px, 8vw, 96px);
            /* mobile → desktop */
        }

        /* Ensure slides center nicely and text scales */
        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 640px) {
            .successSwiper .swiper-slide .text-sm {
                font-size: 0.8rem;
            }

            .successSwiper .swiper-slide .text-2xl {
                font-size: 1.25rem;
            }
        }

        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Back to Admin Alert -->
    {{-- @if(session()->has('impersonated_by_admin'))
    <div
        class="bg-gradient-to-r from-orange-500 to-red-500 text-white py-3 px-4 fixed top-0 left-0 right-0 z-[60] shadow-lg">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-full p-2">
                    <i class="fas fa-user-shield text-lg"></i>
                </div>
                <div>
                    <span class="text-sm font-bold block">Admin Mode</span>
                    <span class="text-xs opacity-90">Viewing as {{ Auth::user()->username }}</span>
                </div>
            </div>
            <form action="{{ route('return-to-admin') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="bg-white text-orange-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                    <i class="fas fa-arrow-left"></i>
                    <span class="hidden sm:inline">Back to Admin</span>
                    <span class="sm:hidden">Back</span>
                </button>
            </form>
        </div>
    </div>
    @endif --}}

    <!-- Header -->
    @include('layouts.user.header')

    <!-- Main Content -->
    <main class="{{ session()->has('impersonated_by_admin') ? 'pt-20 md:pt-24' : 'pt-20 md:pt-24' }} pb-24 px-4">
        <div class="max-w-6xl mx-auto py-4">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation -->
    @include('layouts.user.bottom-nav')

    <!-- PHP Flasher -->
    @flasher_render

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @if(session()->has('impersonated_by_admin'))
        <script>
            // Listen for focus requests from admin panel
            window.addEventListener('storage', function (e) {
                if (e.key === 'user_tab_focus_request' && e.newValue) {
                    const data = JSON.parse(e.newValue);
                    const currentUserId = '{{ Auth::id() }}';

                    if (data.userId == currentUserId) {
                        // Focus this window
                        window.focus();

                        // Notify that this tab was focused
                        localStorage.setItem('user_tab_focused', JSON.stringify({
                            userId: currentUserId,
                            timestamp: Date.now()
                        }));
                        localStorage.removeItem('user_tab_focused');
                    }
                }
            });

            // Send heartbeat to track this tab
            const userId = '{{ Auth::id() }}';
            const tabId = 'tab_' + Date.now() + '_' + Math.random();

            function sendHeartbeat() {
                const openTabs = JSON.parse(localStorage.getItem('impersonated_user_tabs') || '{}');
                openTabs[userId] = openTabs[userId] || {};
                openTabs[userId][tabId] = Date.now();
                localStorage.setItem('impersonated_user_tabs', JSON.stringify(openTabs));
            }

            // Send heartbeat every 2 seconds
            sendHeartbeat();
            const heartbeatInterval = setInterval(sendHeartbeat, 2000);

            // Clean up on window close
            window.addEventListener('beforeunload', function () {
                clearInterval(heartbeatInterval);
                const openTabs = JSON.parse(localStorage.getItem('impersonated_user_tabs') || '{}');
                if (openTabs[userId]) {
                    delete openTabs[userId][tabId];
                    if (Object.keys(openTabs[userId]).length === 0) {
                        delete openTabs[userId];
                    }
                    localStorage.setItem('impersonated_user_tabs', JSON.stringify(openTabs));
                }
            });
        </script>
    @endif

    <!-- Smart Back Navigation -->
    <script>
        // Store current page in sessionStorage for back button
        (function () {
            const currentPage = window.location.pathname;
            const previousPage = sessionStorage.getItem('previousPage');

            // Store the current page for next navigation
            sessionStorage.setItem('previousPage', currentPage);

            // Make previousPage globally available
            window.getPreviousPage = function () {
                return previousPage || '{{ route("dashboard") }}';
            };

            // Listen to back button clicks
            window.addEventListener('popstate', function () {
                sessionStorage.setItem('previousPage', window.location.pathname);
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>