<header
    class="bg-white shadow-sm fixed {{ session()->has('admin_logged_in_as_user') ? 'top-[64px]' : 'top-0' }} left-0 right-0 z-50 mb-3">
    <div class="px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            @if(setting('logo_path'))
                <img src="{{ asset(setting('logo_path')) }}" alt="Logo"
                    class="w-10 h-10 rounded-lg shadow-lg object-contain">
            @else
                <div
                    class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-xl">{{ setting('site_title', config('app.name')) }}</span>
                </div>
            @endif
            <span
                class="font-bold text-gray-800 text-lg hidden sm:block">{{ setting('site_title', config('app.name')) }}</span>
        </a>

        <div class="flex items-center gap-2">
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
            <form method="POST" action="{{ route('locale.switch', app()->getLocale()) }}" class="hidden sm:block"
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
                                    srcset="https://flagcdn.com/40x30/{{ $countryCode }}.png 2x"
                                    alt="{{ strtoupper($code) }}" class="h-[15px] w-5 rounded-sm object-cover">
                                <span>{{ $localeName }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>

            <!-- Notification Icon -->
            <a href="{{ route('notifications.index') }}" class="relative mr-2">
                <div
                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                    <i class="fas fa-bell text-gray-700"></i>
                </div>
                @if(!empty($userNotificationUnread) && $userNotificationUnread > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                        {{ $userNotificationUnread > 99 ? '99+' : $userNotificationUnread }}
                    </span>
                @endif
            </a>

            <!-- User Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2 hover:bg-gray-50 px-3 py-2 rounded-lg transition ">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('uploads/avatar/' . auth()->user()->avatar_path) }}" alt="avatar"
                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white border-2 border-gray-200">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="text-left hidden sm:block">
                        <div class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ "@" . auth()->user()->username }}</div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition x-cloak
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50"
                    style="display: none;">
                    <a href="{{ route('profile.home') }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                        <i class="fas fa-user-circle text-gray-400 w-5"></i>
                        <span class="text-gray-700">{{ __('ui.profile') }}</span>
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                        <i class="fas fa-cog text-gray-400 w-5"></i>
                        <span class="text-gray-700">{{ __('ui.settings') }}</span>
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition text-left">
                            <i class="fas fa-sign-out-alt text-red-500 w-5"></i>
                            <span class="text-red-600">{{ __('ui.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>