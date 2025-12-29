@extends('layouts.user.app')

@section('title', 'Wallet Management - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">Wallet Management</h1>
    </div>

    <div class="bg-gradient-to-br from-rose-600 to-purple-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-wallet text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Wallet Management</h2>
                <div class="text-sm text-white/80">
                    You can't change after setup your wallet.
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <form id="walletForm" action="{{ route('wallet.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="wallet_name" class="block text-sm font-medium text-gray-700 mb-1">Wallet Name <span
                        class="text-red-500">*</span></label>
                <input type="text" id="wallet_name" name="wallet_name"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->wallet_name) bg-gray-50 text-gray-600 cursor-not-allowed @endif"
                    value="{{ old('wallet_name', $user->wallet_name) }}" @if($user->wallet_name)
                    disabled @else required @endif
                    placeholder="@if($user->wallet_name) Name already set @else Enter your withdrawal name @endif">
                @error('wallet_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="wallet_gateway" class="block text-sm font-medium text-gray-700 mb-1">Wallet Currency Protocol
                    <span class="text-red-500">*</span></label>
                <select id="wallet_gateway" name="wallet_gateway"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->wallet_name && $user->wallet_gateway && $user->withdrawal_address) bg-gray-50 cursor-not-allowed @endif"
                    @if($user->wallet_name && $user->wallet_gateway && $user->withdrawal_address) disabled @else
                    required @endif>
                    <option value="" disabled selected>Select Protocol</option>
                    @foreach ($gateways as $gateway)
                        <option value="{{ $gateway->name }}" @if(old('wallet_gateway', $user->wallet_gateway) == $gateway->name)
                        selected @endif>
                            {{ $gateway->name }}
                        </option>
                    @endforeach
                </select>
                @if($user->wallet_name && $user->wallet_gateway && $user->withdrawal_address)
                    <input type="hidden" name="wallet_gateway" value="{{ $user->wallet_gateway }}">
                @endif
                @error('wallet_gateway')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="withdrawal_address" class="block text-sm font-medium text-gray-700 mb-1">Wallet Address <span class="text-red-500">*</span></label>
                <input type="text" id="withdrawal_address" name="withdrawal_address"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->withdrawal_address) bg-gray-50 text-gray-600 cursor-not-allowed @endif"
                    value="{{ old('withdrawal_address', $user->withdrawal_address) }}" @if($user->withdrawal_address)
                    disabled readonly @else required @endif placeholder="Enter your wallet address">
                @if($user->withdrawal_address)
                    <input type="hidden" name="withdrawal_address" value="{{ $user->withdrawal_address }}">
                @endif
                @error('withdrawal_address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- wallet password verification --}}
            @if(!$user->wallet_name && !$user->wallet_gateway && !$user->withdrawal_address)
                <div>
                    <label for="wallet_password" class="block text-sm font-medium text-gray-700 mb-1">Wallet Password (For
                        Verification) <span class="text-red-500">*</span></label>
                    <input type="password" id="wallet_password" name="wallet_password" required
                        class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                        placeholder="Enter your wallet password to verify">
                    <p class="text-xs text-gray-500 mt-1">This is the password you set during registration</p>
                    @error('wallet_password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Important Notes:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Once set, wallet details cannot be changed</li>
                            <li>Ensure the wallet address matches the selected protocol</li>
                            <li>Double-check all details before submission to avoid errors</li>
                        </ul>
                    </div>
                </div>
            </div>

            @endif

            <div class="flex justify-end gap-3">
                {{-- goto support button --}}
                <a href="{{ route('service.index') }}" type="button"
                    class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow">
                    Contact Support
                </a>
                <button type="submit" id="submitBtn" @if($user->wallet_name && $user->wallet_gateway && $user->withdrawal_address) disabled
                    class="px-4 py-2 rounded-lg bg-gray-400 text-white cursor-not-allowed" @else
                    class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 shadow" @endif>
                    @if($user->wallet_name && $user->wallet_gateway && $user->withdrawal_address)
                        Setup Complete
                    @else
                        Save Wallet Details
                    @endif
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
                    <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                    </div>
                </div>
                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2" id="successTitle">Success!</h3>
                <!-- Message -->
                <p id="successMessage" class="text-gray-600 text-center mb-6"></p>
                <!-- Button -->
                <div class="flex gap-3">
                    <button type="button" id="closeSuccessBtn"
                        class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all">
            <div class="p-6">
                <!-- Error Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                    </div>
                </div>
                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2" id="errorTitle">Error</h3>
                <!-- Message -->
                <p id="errorMessage" class="text-gray-700 text-center mb-6 font-medium"></p>
                <!-- Button -->
                <div class="flex gap-3">
                    <button type="button" id="closeErrorBtn"
                        class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('walletForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            fetch('{{ route("wallet.update") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessModal(data.message || 'Wallet details saved successfully.');
                    } else {
                        showErrorModal(data.message || 'Failed to save wallet details.');
                    }
                })
                .catch(error => {
                    showErrorModal('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save Wallet Details';
                });
        });

        function showSuccessModal(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        function showErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.remove('hidden');
            document.getElementById('errorModal').classList.add('flex');
        }

        document.getElementById('closeSuccessBtn').addEventListener('click', function () {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
            window.location.href = '{{ route('wallet.edit') }}';
        });

        document.getElementById('closeErrorBtn').addEventListener('click', function () {
            document.getElementById('errorModal').classList.add('hidden');
            document.getElementById('errorModal').classList.remove('flex');
        });
    </script>
@endsection