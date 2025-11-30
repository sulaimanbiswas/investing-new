@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-10">Login</h1>

    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="relative mb-5">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="text" name="email" placeholder="Username or Email" value="{{ old('email') }}"
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required autofocus>
        </div>

        <div class="relative mb-5">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" id="password" name="password" placeholder="Password"
                class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
            <button type="button" onclick="togglePassword()"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-500 transition-colors">
                <i class="fas fa-eye" id="password-toggle-icon"></i>
            </button>
        </div>

        <div class="mb-5">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
                    <input type="text" name="captcha" id="captcha" placeholder="Verification Code"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                        required>
                </div>
                <div class="bg-gray-200 px-5 py-3 rounded-xl text-xl font-bold tracking-widest text-gray-800 min-w-[120px] text-center select-none"
                    id="captchaDisplay"></div>
                <button type="button" onclick="generateCaptcha()"
                    class="bg-indigo-500 text-white px-4 rounded-xl hover:bg-indigo-600 transition-all">
                    <i class="fas fa-sync-alt hover:rotate-180"></i>
                </button>
            </div>
            <p id="captchaError" class="text-red-600 text-sm mt-2 ml-1 hidden">
                <i class="fas fa-exclamation-circle"></i> Invalid verification code. Please try again.
            </p>
        </div>

        <div class="flex justify-between items-center mb-6 text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-5 h-5 cursor-pointer">
                <span class="text-gray-700">Remember password</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-yellow-500 hover:text-yellow-600 hover:underline transition">Forgot Password?</a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl">Login</button>
    </form>

    @if (Route::has('register'))
        <div class="text-center mt-6 text-sm text-gray-600">
            Don't have an account? <a href="{{ route('register') }}"
                class="text-yellow-500 font-semibold hover:text-yellow-600 hover:underline transition">Register</a>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        let captchaCode = '';

        function generateCaptcha() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            captchaCode = '';
            for (let i = 0; i < 4; i++) {
                captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('captchaDisplay').textContent = captchaCode;
        }

        // Generate captcha on page load
        generateCaptcha();

        // Validate captcha before form submission
        document.querySelector('form').addEventListener('submit', function (e) {
            const captchaInput = document.querySelector('input[name="captcha"]');
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
        document.getElementById('captcha').addEventListener('input', function () {
            this.classList.remove('border-red-500', 'focus:border-red-500');
            this.classList.add('border-gray-200', 'focus:border-indigo-500');
            document.getElementById('captchaError').classList.add('hidden');
        });

        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');

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