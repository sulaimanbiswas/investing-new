@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-10">Register</h1>

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
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required autofocus>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" id="password" name="password" placeholder="Password"
                class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
            <button type="button" onclick="togglePassword('password')"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-500 transition-colors">
                <i class="fas fa-eye" id="password-toggle-icon"></i>
            </button>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" id="withdrawal_password" name="withdrawal_password"
                placeholder="Withdrawal Password (optional)" value="{{ old('withdrawal_password') }}"
                class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50">
            <button type="button" onclick="togglePassword('withdrawal_password')"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-500 transition-colors">
                <i class="fas fa-eye" id="withdrawal_password-toggle-icon"></i>
            </button>
        </div>

        <div class="mb-5">
            <div class="relative">
                <i class="fas fa-users absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
                <input type="text" name="invitation_code" placeholder="Invitation code (required)"
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
                    <span>Invitation code applied from referral link</span>
                </p>
            @endif
        </div>

        <div class="mb-5">
            @if(captcha_enabled())
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
                        <input type="text" id="verification_code" name="verification_code" placeholder="Verification Code"
                            value="{{ old('verification_code') }}"
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
                    <i class="fas fa-exclamation-circle"></i> Invalid verification code. Please try again.
                </p>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl mt-3">Register</button>
    </form>

    <div class="text-center mt-6 text-sm text-gray-600">
        Already have an account? <a href="{{ route('login') }}"
            class="text-yellow-500 font-semibold hover:text-yellow-600 hover:underline transition">Sign In</a>
    </div>
@endsection

@section('scripts')
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
    </script>
@endsection