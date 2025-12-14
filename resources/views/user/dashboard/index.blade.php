@extends('layouts.user.app')

@section('title', 'Home - ' . config('app.name'))

@section('content')
    <!-- Quick Action Icons -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <a href="{{ route('deposit') }}" {{-- bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg
            overflow-hidden mt-2 --}}
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 hover:shadow-md transition bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg">
            <div
                class="w-12 h-12 bg-gradient-to-br to-indigo-500 from-purple-600  rounded-full flex items-center justify-center">
                <i class="fas fa-credit-card text-white text-xl"></i>

            </div>
            <span class="text-xs font-medium  text-white">Recharge</span>
        </a>
        <a href="{{ route('withdrawal') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 hover:shadow-md transition bg-gradient-to-br to-indigo-500 from-purple-600 shadow-lg">
            <div
                class="w-12 h-12 bg-gradient-to-br to-indigo-500 from-purple-600  rounded-full flex items-center justify-center">
                <i class="fas fa-wallet text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-white">Withdrawal</span>
        </a>
        <a href="{{ route('teams') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Teams</span>
        </a>
        <a href="{{ route('invitation') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Invitation</span>
        </a>
    </div>

    <!-- Success Ticker Slider -->
    @include('components.success-slider')

    <!-- Platform Rules Section -->
    @include('components.platform-rules')

    <!-- Referral System Section -->
    <div id="referralSection">
        @include('components.referral-section')
    </div>
@endsection

<!-- Notification Modal -->
@if(setting('notify_heading') || setting('notify_text'))
    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell text-indigo-600 text-4xl"></i>
                    </div>
                </div>

                <!-- Title -->
                @if(setting('notify_heading'))
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">{{ setting('notify_heading') }}</h3>
                @endif

                <!-- Message -->
                @if(setting('notify_text'))
                    <div class="text-gray-600 text-center mb-6 max-h-64 overflow-y-auto px-2">
                        <p class="whitespace-pre-line">{{ setting('notify_text') }}</p>
                    </div>
                @endif

                <!-- Don't Show Again Checkbox -->
                <div class="mb-6 flex items-center justify-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="dontShowAgain"
                            class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Don't show again</span>
                    </label>
                </div>

                <!-- Button -->
                <button type="button" id="closeNotificationBtn"
                    class="w-full bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold py-3 rounded-xl hover:from-indigo-600 hover:to-indigo-700 active:scale-95 transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Notification Modal Logic
            const notificationModal = document.getElementById('notificationModal');
            const closeNotificationBtn = document.getElementById('closeNotificationBtn');
            const dontShowAgainCheckbox = document.getElementById('dontShowAgain');

            // Check if user has chosen to not show notification again
            const dontShowNotification = localStorage.getItem('hideNotification');

            // Show modal on page load if not disabled
            if (!dontShowNotification && notificationModal) {
                setTimeout(() => {
                    notificationModal.classList.remove('hidden');
                    notificationModal.classList.add('flex');
                }, 500); // Small delay for better UX
            }

            // Close notification modal
            if (closeNotificationBtn) {
                closeNotificationBtn.addEventListener('click', function () {
                    // Check if "Don't show again" is checked
                    if (dontShowAgainCheckbox && dontShowAgainCheckbox.checked) {
                        localStorage.setItem('hideNotification', 'true');
                    }

                    notificationModal.classList.add('hidden');
                    notificationModal.classList.remove('flex');
                });
            }

            // Close modal when clicking outside
            if (notificationModal) {
                notificationModal.addEventListener('click', function (e) {
                    if (e.target === notificationModal) {
                        // Check if "Don't show again" is checked
                        if (dontShowAgainCheckbox && dontShowAgainCheckbox.checked) {
                            localStorage.setItem('hideNotification', 'true');
                        }

                        notificationModal.classList.add('hidden');
                        notificationModal.classList.remove('flex');
                    }
                });
            }
        </script>
    @endpush
@endif