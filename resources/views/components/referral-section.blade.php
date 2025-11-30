<!-- Referral System Section -->
<div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg overflow-hidden mt-2">
    <div class="p-4 sm:p-6 text-white">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-4">
            <i class="fas fa-users text-2xl sm:text-3xl"></i>
            <h3 class="text-xl sm:text-2xl font-bold">Referral Program</h3>
        </div>

        <p class="mb-4 text-indigo-100 text-sm sm:text-base">Share your unique referral code and earn rewards!</p>

        <!-- Referral Code -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
            <label class="text-xs sm:text-sm text-indigo-100 block mb-2">Your Referral Code</label>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <input type="text" readonly value="{{ auth()->user()->referral_code }}" id="referralCode"
                    class="flex-1 bg-white text-gray-900 font-mono font-bold text-lg sm:text-xl px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-0 focus:ring-2 focus:ring-yellow-400">
                <button onclick="copyReferralCode(this)"
                    class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg transition-all hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i>
                    <span>Copy</span>
                </button>
            </div>
        </div>

        <!-- Referral Link -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
            <label class="text-xs sm:text-sm text-indigo-100 block mb-2">Your Referral Link</label>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <input type="text" readonly value="{{ auth()->user()->referral_link }}" id="referralLink"
                    class="flex-1 bg-white text-gray-900 font-mono text-xs sm:text-sm px-3 sm:px-4 py-2 sm:py-3 rounded-lg border-0 focus:ring-2 focus:ring-yellow-400 truncate">
                <button onclick="copyReferralLink(this)"
                    class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg transition-all hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i>
                    <span>Copy</span>
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <div class="text-2xl sm:text-3xl font-bold mb-1">{{ auth()->user()->referral_count }}</div>
                <div class="text-indigo-100 text-xs sm:text-sm">Total Referrals</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <div class="text-xl sm:text-2xl font-bold mb-1 truncate">
                    {{ auth()->user()->referrer ? auth()->user()->referrer->name : 'None' }}
                </div>
                <div class="text-indigo-100 text-xs sm:text-sm">Referred By</div>
            </div>
        </div>

        <!-- Referrals List -->
        @if(auth()->user()->referral_count > 0)
            <div class="mt-4 bg-white/10 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <h4 class="font-semibold mb-3 text-sm sm:text-base">Your Referrals ({{ auth()->user()->referral_count }})
                </h4>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach(auth()->user()->referrals()->latest()->take(10)->get() as $referral)
                        <div class="bg-white/10 rounded-lg p-3 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($referral->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-sm sm:text-base">{{ $referral->name }}</div>
                                    <div class="text-xs text-indigo-200">@{{ $referral->username }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-indigo-200">{{ $referral->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function setCopiedState(btn) {
            const icon = btn.querySelector('i');
            const label = btn.querySelector('span');
            const originalIcon = icon.className;
            const originalText = label.textContent;

            icon.className = 'fas fa-clipboard-check';
            label.textContent = 'Copied';
            btn.classList.add('bg-green-500');
            btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');

            setTimeout(() => {
                icon.className = originalIcon;
                label.textContent = originalText;
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
            }, 5000);
        }

        function copyReferralCode(btn) {
            const codeInput = document.getElementById('referralCode');
            codeInput.select();
            codeInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(codeInput.value).then(() => {
                setCopiedState(btn);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }

        function copyReferralLink(btn) {
            const linkInput = document.getElementById('referralLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(linkInput.value).then(() => {
                setCopiedState(btn);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    </script>
@endpush