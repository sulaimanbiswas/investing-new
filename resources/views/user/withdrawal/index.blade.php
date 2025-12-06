@extends('layouts.user.app')

@section('title', 'Withdrawal - ' . config('app.name'))

@section('content')
    <div class="bg-gradient-to-br from-green-600 to-teal-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-white text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-white/80">Request withdrawal</div>
                <h2 class="text-2xl font-bold">Withdrawal</h2>
            </div>
        </div>
    </div>

    <!-- Available Balance Card -->
    @if($user->freeze_amount > 0)
        <!-- With Frozen Amount -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 mb-5 border-2 border-blue-100">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Your Balance Overview</p>
                    <h3 class="text-lg font-bold text-gray-900">Account Balance</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 mb-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-coins text-blue-500"></i>
                        Total Balance
                    </span>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($user->balance, 2) }}</span>
                </div>
            </div>

            <div class="bg-red-50 rounded-xl p-4 mb-3 border border-red-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-red-700 flex items-center gap-2">
                        <i class="fas fa-lock text-red-500"></i>
                        Frozen Amount
                    </span>
                    <span class="text-xl font-bold text-red-600">- {{ number_format($user->freeze_amount, 2) }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Available to Withdraw
                        </p>
                        <p class="text-white text-4xl font-bold">{{ number_format($availableBalance, 2) }}</p>
                    </div>
                    <div class="bg-white/20 rounded-full p-4">
                        <i class="fas fa-money-bill-wave text-white text-3xl"></i>
                    </div>
                </div>
                <p class="text-green-100 text-xs mt-2">USDT</p>
            </div>
        </div>
    @else
        <!-- Without Frozen Amount - Clean Design -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-8 mb-5 relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>

            <div class="relative">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-wallet text-white text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-green-100 text-sm">Available Balance</p>
                        <p class="text-white text-xs opacity-90">Ready to withdraw</p>
                    </div>
                </div>

                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-white text-5xl font-bold mb-1">{{ number_format($availableBalance, 2) }}</p>
                        <p class="text-green-100 text-lg font-medium">USDT</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl p-4 backdrop-blur-sm">
                        <i class="fas fa-arrow-circle-down text-white text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Withdrawal Request</h3>

        <form id="withdrawalForm" class="space-y-4">
            @csrf
            <div>
                <label for="wallet_address" class="block text-sm font-medium text-gray-700 mb-1">Wallet Address (USDT
                    TRC20)</label>
                <input type="text" id="wallet_address" name="wallet_address"
                    class="w-full rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500"
                    value="{{ old('wallet_address', $user->withdrawal_address) }}" required>
                <p class="text-xs text-gray-500 mt-1">You can edit your default wallet address here</p>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Amount (USDT)</label>
                <div class="relative">
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01"
                        class="w-full rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500 pr-16"
                        placeholder="Enter amount" required>
                    <button type="button" id="maxBtn"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600 transition-colors">
                        MAX
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Available: {{ number_format($availableBalance, 2) }} USDT</p>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Important Notes:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Please ensure your wallet address is correct</li>
                            <li>Withdrawal will be processed after admin approval</li>
                            <li>Processing time: 1-24 hours</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('profile.home') }}"
                    class="flex-1 px-4 py-3 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-center">Cancel</a>
                <button type="submit"
                    class="flex-1 px-4 py-3 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow font-semibold">
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full transform transition-all">
            <div class="p-6">
                <!-- Success Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Request Submitted!</h3>

                <!-- Message -->
                <p id="successMessage" class="text-gray-600 text-center mb-6"></p>

                <!-- Button -->
                <button type="button" id="closeSuccessBtn"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-xl hover:from-green-600 hover:to-green-700 active:scale-95 transition-all duration-200">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full transform transition-all">
            <div class="p-6">
                <!-- Error Icon -->
                <div class="flex justify-center mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Error</h3>

                <!-- Message -->
                <p id="errorMessage" class="text-gray-600 text-center mb-6"></p>

                <!-- Button -->
                <button type="button" id="closeErrorBtn"
                    class="w-full bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('withdrawalForm');
        const successModal = document.getElementById('successModal');
        const errorModal = document.getElementById('errorModal');
        const closeSuccessBtn = document.getElementById('closeSuccessBtn');
        const closeErrorBtn = document.getElementById('closeErrorBtn');
        const maxBtn = document.getElementById('maxBtn');
        const amountInput = document.getElementById('amount');

        // MAX button click handler
        maxBtn.addEventListener('click', function () {
            const availableBalance = {{ $availableBalance }};
            amountInput.value = availableBalance.toFixed(2);
            amountInput.focus();
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            try {
                const response = await fetch('{{ route('withdrawal.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('successMessage').textContent = data.message;
                    successModal.classList.remove('hidden');
                    successModal.classList.add('flex');
                } else {
                    document.getElementById('errorMessage').textContent = data.message;
                    errorModal.classList.remove('hidden');
                    errorModal.classList.add('flex');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
                errorModal.classList.remove('hidden');
                errorModal.classList.add('flex');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Request';
            }
        });

        closeSuccessBtn.addEventListener('click', function () {
            successModal.classList.add('hidden');
            successModal.classList.remove('flex');
            window.location.href = '{{ route('withdrawal.records') }}';
        });

        closeErrorBtn.addEventListener('click', function () {
            errorModal.classList.add('hidden');
            errorModal.classList.remove('flex');
        });
    </script>
@endpush