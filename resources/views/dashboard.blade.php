<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __("You're logged in!") }}</p>
                </div>
            </div>

            <!-- Referral Card -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="text-2xl font-bold">Your Referral Program</h3>
                    </div>

                    <p class="mb-4 text-indigo-100">Share your unique referral code and earn rewards when your friends
                        join!</p>

                    <!-- Referral Code Display -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-4">
                        <label class="text-sm text-indigo-100 block mb-2">Your Referral Code</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ auth()->user()->referral_code }}" id="referralCode"
                                class="flex-1 bg-white text-gray-900 font-mono font-bold text-xl px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-yellow-400">
                            <button onclick="copyReferralCode()"
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-6 py-3 rounded-lg transition-all hover:scale-105 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>

                    <!-- Referral Link Display -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-4">
                        <label class="text-sm text-indigo-100 block mb-2">Your Referral Link</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ auth()->user()->referral_link }}" id="referralLink"
                                class="flex-1 bg-white text-gray-900 font-mono text-sm px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-yellow-400">
                            <button onclick="copyReferralLink()"
                                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-6 py-3 rounded-lg transition-all hover:scale-105 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <div class="text-3xl font-bold mb-1">{{ auth()->user()->referral_count }}</div>
                            <div class="text-indigo-100 text-sm">Total Referrals</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <div class="text-3xl font-bold mb-1">
                                {{ auth()->user()->referrer ? auth()->user()->referrer->name : 'None' }}</div>
                            <div class="text-indigo-100 text-sm">Referred By</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral List (if any) -->
            @if(auth()->user()->referrals()->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Your Referrals</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Username</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Joined Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach(auth()->user()->referrals()->latest()->get() as $referral)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $referral->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $referral->username }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $referral->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function copyReferralCode() {
                const codeInput = document.getElementById('referralCode');
                codeInput.select();
                codeInput.setSelectionRange(0, 99999); // For mobile devices

                navigator.clipboard.writeText(codeInput.value).then(() => {
                    // Show success notification using Flasher
                    flasher.success('Referral code copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }

            function copyReferralLink() {
                const linkInput = document.getElementById('referralLink');
                linkInput.select();
                linkInput.setSelectionRange(0, 99999); // For mobile devices

                navigator.clipboard.writeText(linkInput.value).then(() => {
                    // Show success notification using Flasher
                    flasher.success('Referral link copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
        </script>
    @endpush
</x-app-layout>