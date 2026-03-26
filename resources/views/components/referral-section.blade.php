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
        @php
            // Calculate 3-level team size like teams page
            $level1 = auth()->user()->referrals;
            $level1Count = $level1->count();

            $level2Count = 0;
            if ($level1Count > 0) {
                $level2Count = \App\Models\User::whereIn('referred_by', $level1->pluck('id'))->count();
            }

            $level3Count = 0;
            if ($level2Count > 0) {
                $level2Ids = \App\Models\User::whereIn('referred_by', $level1->pluck('id'))->pluck('id');
                $level3Count = \App\Models\User::whereIn('referred_by', $level2Ids)->count();
            }

            $totalTeamSize = $level1Count + $level2Count + $level3Count;
        @endphp
        <div class="grid grid-cols-2 gap-3 sm:gap-4">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <div class="text-2xl sm:text-3xl font-bold mb-1">{{ $totalTeamSize }}</div>
                <div class="text-indigo-100 text-xs sm:text-sm">Total Referrals (3 Levels)</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <div class="text-xl sm:text-2xl font-bold mb-1 truncate">
                    {{ auth()->user()->referrer ? auth()->user()->referrer->name : 'None' }}
                </div>
                <div class="text-indigo-100 text-xs sm:text-sm">Referred By</div>
            </div>
        </div>

        <!-- Referrals List -->
        @if($level1Count > 0)
            <div class="mt-4 bg-white/10 backdrop-blur-sm rounded-lg p-3 sm:p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-sm sm:text-base">Your Direct Referrals ({{ $level1Count }})
                    </h4>
                    @if($level1Count > 5)
                        <a href="{{ route('teams') }}"
                            class="text-xs sm:text-sm text-yellow-300 hover:text-yellow-100 font-semibold flex items-center gap-1">
                            <span>View All</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach(auth()->user()->referrals()->latest()->take(5)->get() as $referral)
                        @php
                            $depositAmount = (float) ($referral->deposits()->where('status', 'approved')->sum('amount') ?? 0);
                            $withdrawAmount = (float) ($referral->withdrawals()->where('status', 'approved')->sum('amount') ?? 0);
                        @endphp
                        <div class="bg-white/10 rounded-lg p-3 flex items-center justify-between text-sm">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($referral->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-sm sm:text-base">{{ $referral->name }}</div>
                                    <div class="text-xs text-indigo-200">{{ $referral->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="text-right leading-tight">
                                <div class="text-xs text-indigo-200">Deposit: ${{ number_format($depositAmount, 2) }}</div>
                                <div class="text-xs text-indigo-200">Withdraw: ${{ number_format($withdrawAmount, 2) }}</div>
                            </div>
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