<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

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
    <!-- Header -->
    @include('layouts.user.header')

    <!-- Main Content -->
    <main class="pt-20 md:pt-24 pb-24 px-4">
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

    @stack('scripts')
</body>

</html>