@extends('layouts.auth')

@section('title', __('ui.register_now'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }

        .iti__country-container {
            left: 0;
        }

        .iti__selected-country {
            padding-left: 12px;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        #phone {
            padding-left: 60px !important;
        }

        [dir="rtl"] .iti__country-container {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .iti__selected-country {
            padding-left: 0;
            padding-right: 12px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        [dir="rtl"] #phone {
            padding-left: 12px !important;
            padding-right: 60px !important;
        }
    </style>
@endpush

@section('content')
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-10">{{ __('ui.register_now') }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="relative mb-5">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="text" name="name" placeholder="{{ __('ui.full_name') }}" value="{{ old('name') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required autofocus>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="text" name="username" placeholder="{{ __('ui.username') }}" value="{{ old('username') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="tel" id="phone" name="phone" placeholder="{{ __('ui.phone_number_required') }}"
                value="{{ old('phone') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                inputmode="tel" autocomplete="tel" required>
            <input type="hidden" id="phone_country" name="phone_country" value="{{ old('phone_country') }}">
            <p id="phone-format-hint" class="mt-2 ml-1 text-xs text-gray-500"></p>
        </div>
        {{--
        <div class="relative mb-5">
            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="email" name="email" placeholder="Email Address (Optional)" value="{{ old('email') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50">
        </div> --}}

        <div class="relative mb-5">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" id="password" name="password" placeholder="{{ __('ui.password') }}"
                class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                minlength="6" required>
            <button type="button" onclick="togglePassword('password')"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-500 transition-colors">
                <i class="fas fa-eye" id="password-toggle-icon"></i>
            </button>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" id="withdrawal_password" name="withdrawal_password"
                placeholder="{{ __('ui.withdrawal_password') }}" value="{{ old('withdrawal_password') }}" required
                minlength="6"
                class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50">
            <button type="button" onclick="togglePassword('withdrawal_password')"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-500 transition-colors">
                <i class="fas fa-eye" id="withdrawal_password-toggle-icon"></i>
            </button>
        </div>

        <div class="mb-5">
            <div class="relative">
                <i class="fas fa-users absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
                <input type="text" name="invitation_code" placeholder="{{ __('ui.invitation_code_required') }}"
                    value="{{ old('invitation_code', $referralCode ?? '') }}"
                    class="w-full pl-12 pr-4 py-3 border-2 {{ isset($referralCode) && $referralCode ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-indigo-50' }} rounded-xl text-base transition-all focus:outline-none {{ isset($referralCode) && $referralCode ? 'cursor-not-allowed' : 'focus:border-indigo-500 focus:bg-white' }}"
                    {{ isset($referralCode) && $referralCode ? 'readonly' : '' }} required>
                @if(isset($referralCode) && $referralCode)
                    <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-green-600 text-sm"></i>
                @endif
            </div>
            @if(isset($referralCode) && $referralCode)
                <p class="text-xs text-green-600 mt-2 ml-1 flex items-center gap-1">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('ui.invitation_code_applied') }}</span>
                </p>
            @endif
        </div>

        <div class="mb-5">
            @if(captcha_enabled())
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
                        <input type="text" id="verification_code" name="verification_code"
                            placeholder="{{ __('ui.verification_code') }}" value="{{ old('verification_code') }}"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                            required>
                    </div>
                    <div class="bg-gray-200 px-5 py-3 rounded-xl text-xl font-bold tracking-widest text-gray-800 min-w-[120px] text-center select-none"
                        id="captcha_display"></div>
                    <button type="button" onclick="generateCaptcha()"
                        class="bg-indigo-500 text-white px-4 rounded-xl hover:bg-indigo-600 transition-all">
                        <i class="fas fa-sync-alt hover:rotate-180"></i>
                    </button>
                </div>
                <p id="captchaError" class="text-red-600 text-sm mt-2 ml-1 hidden">
                    <i class="fas fa-exclamation-circle"></i> {{ __('ui.invalid_verification_code') }}
                </p>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl mt-3">{{ __('ui.register_now') }}</button>
    </form>

    <div class="text-center mt-6 text-sm text-gray-600">
        {{ __('ui.already_have_account') }} <a href="{{ route('login') }}"
            class="text-yellow-500 font-semibold hover:text-yellow-600 hover:underline transition">{{ __('ui.sign_in') }}</a>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js"></script>
    <script>
        @if(captcha_enabled())
            let captchaCode = '';

            function generateCaptcha() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                captchaCode = '';
                for (let i = 0; i < 4; i++) {
                    captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('captcha_display').textContent = captchaCode;
            }

            // Generate captcha on page load
            generateCaptcha();

            document.querySelector('form').addEventListener('submit', function (e) {
                const captchaInput = document.getElementById('verification_code');
                const captchaError = document.getElementById('captchaError');
                const userCaptcha = captchaInput.value.toUpperCase();

                if (userCaptcha !== captchaCode) {
                    e.preventDefault();

                    // Show inline error and red border
                    captchaInput.classList.remove('border-gray-200', 'focus:border-indigo-500');
                    captchaInput.classList.add('border-red-500', 'focus:border-red-500');
                    captchaError.classList.remove('hidden');

                    generateCaptcha();
                    captchaInput.value = '';
                    captchaInput.focus();
                } else {
                    // Reset error state
                    captchaInput.classList.remove('border-red-500', 'focus:border-red-500');
                    captchaInput.classList.add('border-gray-200', 'focus:border-indigo-500');
                    captchaError.classList.add('hidden');
                }
            });

            // Clear error on input
            document.getElementById('verification_code').addEventListener('input', function () {
                this.classList.remove('border-red-500', 'focus:border-red-500');
                this.classList.add('border-gray-200', 'focus:border-indigo-500');
                document.getElementById('captchaError').classList.add('hidden');
            });
        @endif

            // Toggle password visibility
            function togglePassword(fieldId) {
                const passwordField = document.getElementById(fieldId);
                const toggleIcon = document.getElementById(fieldId + '-toggle-icon');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            }

        // Country-aware phone input with automatic dialing code
        const registerForm = document.querySelector('form[action="{{ route('register') }}"]');
        const phoneInput = document.getElementById('phone');
        const phoneCountryInput = document.getElementById('phone_country');

        let phoneIti = null;
        let lastDialCode = '';
        let autoCountryResolved = false;

        function updatePhoneFormatHint() {
            const phoneFormatHint = document.getElementById('phone-format-hint');
            if (!phoneIti || !phoneFormatHint) {
                return;
            }

            const selected = phoneIti.getSelectedCountryData();
            const placeholder = phoneInput.getAttribute('placeholder') || '';
            const countryName = selected.name || 'selected country';

            if (placeholder) {
                phoneFormatHint.textContent = `Example for ${countryName}: ${placeholder}`;
                return;
            }

            const dialCode = selected.dialCode ? `+${selected.dialCode}` : '';
            phoneFormatHint.textContent = dialCode
                ? `Enter a valid ${countryName} number starting with ${dialCode}`
                : `Enter a valid phone number for ${countryName}`;
        }

        function extractSubscriberNumber(value, dialCode) {
            const normalized = String(value || '').trim();
            const numericDialCode = String(dialCode || '').replace(/\D/g, '');

            if (!normalized) {
                return '';
            }

            if (!numericDialCode) {
                return normalized;
            }

            const digitsOnly = normalized.replace(/\D/g, '');
            if (!digitsOnly.startsWith(numericDialCode)) {
                return normalized.replace(/^\+/, '').trim();
            }

            return digitsOnly.slice(numericDialCode.length);
        }

        function setPhoneWithDialCode(dialCode, subscriberNumber = '') {
            if (!phoneInput) {
                return;
            }

            const cleanDialCode = String(dialCode || '').replace(/\D/g, '');
            let cleanSubscriber = String(subscriberNumber || '').replace(/\D/g, '');

            // Prevent duplicated country prefix when switching countries repeatedly.
            if (cleanDialCode && cleanSubscriber.startsWith(`00${cleanDialCode}`)) {
                cleanSubscriber = cleanSubscriber.slice(cleanDialCode.length + 2);
            } else if (cleanDialCode && cleanSubscriber.startsWith(cleanDialCode)) {
                cleanSubscriber = cleanSubscriber.slice(cleanDialCode.length);
            }

            phoneInput.value = cleanDialCode ? `+${cleanDialCode}${cleanSubscriber}` : cleanSubscriber;
        }

        function syncCountryMeta() {
            if (!phoneIti || !phoneCountryInput) {
                return;
            }

            const selected = phoneIti.getSelectedCountryData();
            phoneCountryInput.value = (selected.iso2 || '').toLowerCase();
            lastDialCode = selected.dialCode || '';
            updatePhoneFormatHint();
        }

        function resolveInitialCountry(callback) {
            const storedCountry = (phoneCountryInput?.value || '').toLowerCase();
            if (storedCountry) {
                autoCountryResolved = true;
                callback(storedCountry);
                return;
            }

            const providers = [
                function () {
                    return fetch('https://ipwho.is/?fields=country_code')
                        .then(function (response) {
                            return response.ok ? response.json() : null;
                        })
                        .then(function (data) {
                            return data?.country_code || '';
                        });
                },
                function () {
                    return fetch('https://ipapi.co/json/')
                        .then(function (response) {
                            return response.ok ? response.json() : null;
                        })
                        .then(function (data) {
                            return data?.country_code || '';
                        });
                },
                function () {
                    return fetch('https://ipapi.co/country/')
                        .then(function (response) {
                            return response.ok ? response.text() : '';
                        });
                },
            ];

            const safeCallback = function (country) {
                autoCountryResolved = true;
                callback(country);
            };

            const tryNext = function (index) {
                if (index >= providers.length) {
                    safeCallback('bd');
                    return;
                }

                providers[index]()
                    .then(function (code) {
                        const iso2 = String(code || '').trim().toLowerCase();
                        if (iso2) {
                            safeCallback(iso2);
                            return;
                        }
                        tryNext(index + 1);
                    })
                    .catch(function () {
                        tryNext(index + 1);
                    });
            };

            tryNext(0);
        }

        if (phoneInput && window.intlTelInput) {
            phoneIti = window.intlTelInput(phoneInput, {
                initialCountry: 'auto',
                geoIpLookup: resolveInitialCountry,
                preferredCountries: ['bd', 'in', 'pk', 'my', 'sa', 'ae', 'uk', 'us'],
                separateDialCode: false,
                nationalMode: false,
                autoInsertDialCode: false,
                autoPlaceholder: 'aggressive',
                formatOnDisplay: true,
                strictMode: false,
            });

            const initialCountry = phoneIti.getSelectedCountryData();
            lastDialCode = initialCountry.dialCode || '';

            const hasStoredCountry = Boolean((phoneCountryInput?.value || '').trim());
            if (!String(phoneInput.value || '').trim() && lastDialCode && hasStoredCountry) {
                setPhoneWithDialCode(lastDialCode);
            } else if (String(phoneInput.value || '').trim().startsWith('+')) {
                phoneIti.setNumber(phoneInput.value);
                syncCountryMeta();
            }

            updatePhoneFormatHint();

            if (phoneCountryInput) {
                phoneInput.addEventListener('countrychange', function () {
                    const selected = phoneIti.getSelectedCountryData();
                    const nextDialCode = selected.dialCode || '';
                    const subscriberNumber = extractSubscriberNumber(phoneInput.value, lastDialCode);

                    setPhoneWithDialCode(nextDialCode, subscriberNumber);
                    autoCountryResolved = true;
                    syncCountryMeta();
                });
            }

            phoneInput.addEventListener('focus', function () {
                const hasStoredCountry = Boolean((phoneCountryInput?.value || '').trim());
                if (!String(this.value || '').trim() && lastDialCode && (autoCountryResolved || hasStoredCountry)) {
                    setPhoneWithDialCode(lastDialCode);
                }
            });

            phoneInput.addEventListener('input', function () {
                const rawValue = String(this.value || '').trim();
                this.setCustomValidity('');

                if (!rawValue) {
                    return;
                }

                if (!rawValue.startsWith('+')) {
                    setPhoneWithDialCode(lastDialCode, rawValue);
                    return;
                }

                // Let the library infer and sync the country from typed international code.
                phoneIti.setNumber(rawValue);
                syncCountryMeta();
            });
        }

        if (registerForm && phoneInput) {
            registerForm.addEventListener('submit', function (e) {
                let normalized = (phoneInput.value || '').trim();

                if (phoneIti) {
                    const selected = phoneIti.getSelectedCountryData();
                    if (phoneCountryInput) {
                        phoneCountryInput.value = (selected.iso2 || '').toLowerCase();
                    }

                    // Re-sync using the typed number to avoid country dropdown mismatch false negatives.
                    phoneIti.setNumber(normalized);
                    syncCountryMeta();

                    const e164Like = normalized.replace(/\s+/g, '');
                    const looksLikeInternational = /^\+[1-9]\d{7,14}$/.test(e164Like);
                    const pluginValid = typeof phoneIti.isValidNumber === 'function'
                        ? phoneIti.isValidNumber()
                        : false;
                    const pluginPossible = typeof phoneIti.isPossibleNumber === 'function'
                        ? phoneIti.isPossibleNumber()
                        : false;

                    if (!pluginValid && !pluginPossible && !looksLikeInternational) {
                        e.preventDefault();
                        phoneInput.setCustomValidity('Please enter a valid phone number including country code.');
                        phoneInput.reportValidity();
                        return;
                    }

                    normalized = phoneIti.getNumber() || normalized; // E.164 format, e.g. +8801...
                }

                const digitsOnly = normalized.replace(/\D/g, '');
                if (digitsOnly.length < 9) {
                    e.preventDefault();
                    phoneInput.setCustomValidity('Phone number must contain at least 9 digits.');
                    phoneInput.reportValidity();
                    return;
                }

                phoneInput.value = normalized;
                phoneInput.setCustomValidity('');
            });

            phoneInput.addEventListener('input', function () {
                this.setCustomValidity('');
            });
        }
    </script>
@endsection