@extends('layouts.user.app')

@section('title', 'Withdrawal - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">Withdrawal Request</h1>
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

            <div class="relative flex justify-between items-center gap-3">
                <div class="flex flex-col items-center gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-wallet text-white text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-green-100 text-sm">Available Balance</p>
                            <p class="text-white text-xs opacity-90">Ready to withdraw</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-white text-3xl font-bold mb-1">{{ number_format($availableBalance, 2) }}</p>
                        <p class="text-green-100 text-lg font-medium">USDT</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 ">
                <p id="wallet-address-display" class="text-sm font-semibold text-white break-all">
                    @if($user->withdrawal_address)
                        {{ $user->withdrawal_address }}
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- Withdrawal Gateway Selection -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Withdrawal Method</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @forelse($gateways as $gateway)
                <label class="gateway-card cursor-pointer">
                    <input type="radio" name="gateway" value="{{ $gateway->id }}" class="hidden gateway-radio" required
                        data-min="{{ $gateway->min_limit }}" data-max="{{ $gateway->max_limit }}"
                        data-currency="{{ $gateway->currency }}"
                        data-custom-fields="{{ json_encode($gateway->custom_fields ?? []) }}" @if($loop->first) checked @endif>
                    <div
                        class="border-2 flex items-center gap-2 justify-start border-gray-200 rounded-xl py-3 px-2  transition hover:border-green-400 hover:bg-green-50">
                        @if($gateway->logo_path)
                            <img src="{{ asset($gateway->logo_path) }}" alt="{{ $gateway->name }}"
                                class="w-6 h-6 pl-2  mb-2 object-contain">
                        @else
                            <div
                                class="w-6 h-6 pl-2  mb-2 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-wallet text-white"></i>
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-gray-800 text-sm">{{ $gateway->name }}</div>
                        </div>
                </label>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                    <p>No withdrawal methods available</p>
                </div>
            @endforelse
        </div>
        <p class="text-xs text-gray-500 mt-3" id="gateway-error"></p>
    </div>

    <!-- Withdrawal Form -->
    <div class="bg-white rounded-xl shadow-sm ">

        <form id="withdrawalForm" class="space-y-4">
            @csrf
            <input type="hidden" id="gateway_id" name="gateway_id">

            {{-- @if($user->withdrawal_address) --}}
            <!-- Wallet Address Display -->
            {{-- <div id="wallet-address-section">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                    <p class="text-xs text-gray-600 font-medium mb-2">Your Withdrawal Address</p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <p id="wallet-address-display" class="text-sm font-semibold text-gray-900 break-all">
                                @if($user->withdrawal_address)
                                {{ $user->withdrawal_address }}
                                @else
                                No address set
                                @endif
                            </p>
                        </div>
                        <button type="button" id="copyWalletBtn"
                            class="text-green-600 hover:text-green-700 transition flex-shrink-0">
                            <i class="fas fa-copy text-lg"></i>
                        </button>
                    </div>
                </div>
            </div> --}}
            {{-- @endif --}}

            <!-- Withdrawal Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Amount</label>
                <div
                    class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden focus-within:border-green-500 transition">
                    <span class="px-2 py-3 bg-gray-50 text-gray-700 font-semibold" id="currency-label">USDT</span>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01"
                        class="flex-1 px-4 py-3 border-0 focus:ring-0 focus:outline-none" placeholder="Enter amount"
                        required>
                    <button type="button" id="maxBtn" {{--
                        class="h-full px-2 py-3.5 bg-green-500 text-white font-semibold rounded-none rounded-r-xl focus:outline-none focus:ring-2 focus:ring-green-400 active:scale-95 transition-all duration-200 min-w-[48px] max-w-[60px] text-xs flex items-center justify-center whitespace-nowrap"
                        --}}>
                        {{-- MAX --}}
                    </button>

                </div>
                <p class="text-xs text-red-600 mt-1" id="amount-error"></p>
            </div>

            <!-- Custom Fields Container -->
            <div id="custom-fields-container"></div>

            <!-- Withdrawal Password -->
            <div>
                <label for="withdrawal_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Withdrawal Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="withdrawal_password" name="withdrawal_password"
                    class="w-full rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500"
                    placeholder="Enter your withdrawal password" required>
                <p class="text-xs text-gray-500 mt-1">Enter the password you set during registration</p>
            </div>

            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('profile.home') }}"
                    class="flex-1 px-4 py-3 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 text-center">Cancel</a>
                <button type="submit" id="submit-btn" disabled
                    class="flex-1 px-4 py-3 rounded-lg bg-gray-400 text-white shadow font-semibold disabled:cursor-not-allowed transition">
                    Withdraw
                </button>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Important Notes:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Please ensure all information is correct</li>
                            <li>Withdrawal will be processed after admin approval</li>
                            <li>Processing time: 1-24 hours</li>
                        </ul>
                    </div>
                </div>
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

                <div class="flex gap-3">
                    <button type="button" id="closeSuccessBtn"
                        class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                        Close
                    </button>
                    <a href="{{ route('service.index') }}" id="gotoMenuBtn"
                        class="flex-1 bg-green-500 text-white font-bold py-3 rounded-xl hover:bg-green-600 active:scale-95 transition-all duration-200 text-center">
                        Help Center
                    </a>
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
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">
                    Withdrawal Error
                </h3>

                <!-- Message -->
                <p id="errorMessage" class="text-gray-700 text-center mb-6 font-medium"></p>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" id="closeErrorBtn"
                        class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                        Close
                    </button>
                    <a href="{{ route('menu.index') }}" id="gotoMenuBtn"
                        class="flex-1 bg-green-500 text-white font-bold py-3 rounded-xl hover:bg-green-600 active:scale-95 transition-all duration-200 text-center">
                        Go to Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const defaultWalletAddress = @json($user->withdrawal_address);
        const form = document.getElementById('withdrawalForm');
        const successModal = document.getElementById('successModal');
        const errorModal = document.getElementById('errorModal');
        const closeSuccessBtn = document.getElementById('closeSuccessBtn');
        const closeErrorBtn = document.getElementById('closeErrorBtn');
        const maxBtn = document.getElementById('maxBtn');
        const amountInput = document.getElementById('amount');
        const customFieldsContainer = document.getElementById('custom-fields-container');
        const submitBtn = document.getElementById('submit-btn');
        const walletAddressSection = document.getElementById('wallet-address-section');
        const walletAddressDisplay = document.getElementById('wallet-address-display');
        const copyWalletBtn = document.getElementById('copyWalletBtn');

        let selectedGateway = null;
        let minLimit = 0;
        let maxLimit = 0;
        let customFields = [];

        // Gateway selection
        document.querySelectorAll('.gateway-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                selectedGateway = this.value;
                minLimit = parseFloat(this.dataset.min);
                maxLimit = parseFloat(this.dataset.max);
                const currency = this.dataset.currency;
                customFields = JSON.parse(this.dataset.customFields || '[]');

                // Update UI
                document.querySelectorAll('.gateway-card > div').forEach(card => {
                    card.classList.remove('border-green-500', 'bg-green-50');
                    card.classList.add('border-gray-200');
                });
                this.parentElement.querySelector('div').classList.remove('border-gray-200');
                this.parentElement.querySelector('div').classList.add('border-green-500', 'bg-green-50');

                document.getElementById('gateway_id').value = selectedGateway;
                document.getElementById('currency-label').textContent = currency;
                document.getElementById('amount-hint').textContent = `Available: {{ number_format($availableBalance, 2) }} USDT | Min: ${minLimit} ${currency} | Max: ${maxLimit} ${currency}`;
                document.getElementById('gateway-error').textContent = '';

                // Show wallet address section with default address
                walletAddressSection.classList.remove('hidden');
                walletAddressDisplay.textContent = defaultWalletAddress || 'No address set';

                // Render custom fields
                renderCustomFields();
                validateForm();
            });
        });

        // Auto-select gateway id=1 on page load
        // On page load: check pending withdrawals, then auto-select first gateway
        window.addEventListener('DOMContentLoaded', async function () {
            try {
                const resp = await fetch('{{ route('withdrawal.check-pending-withdrawal') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    }
                });

                const data = await resp.json();

                if (data.has_pending_withdrawals) {
                    // Show message and disable form
                    document.getElementById('errorMessage').textContent = 'You have already a pending withdrawal request. Please contact support.';
                    errorModal.classList.remove('hidden');
                    errorModal.classList.add('flex');

                    // Disable form controls so user cannot create a new request
                    const controls = form.querySelectorAll('input, button, textarea, select');
                    controls.forEach(el => {
                        // keep close button enabled
                        if (el.id === 'closeErrorBtn' || el.id === 'gotoMenuBtn') return;
                        el.disabled = true;
                    });

                    return;
                }
            } catch (err) {
                console.error('Error checking pending withdrawals:', err);
            }

            // Auto-select the first gateway
            const gatewayRadio = document.querySelector('.gateway-radio');
            if (gatewayRadio) {
                gatewayRadio.checked = true;
                gatewayRadio.dispatchEvent(new Event('change'));
            }
        });

        // Copy wallet address
        if (copyWalletBtn) {
            copyWalletBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const address = walletAddressDisplay.textContent;
                navigator.clipboard.writeText(address).then(() => {
                    const originalIcon = copyWalletBtn.innerHTML;
                    copyWalletBtn.innerHTML = '<i class="fas fa-check text-green-600 text-lg"></i>';
                    setTimeout(() => {
                        copyWalletBtn.innerHTML = originalIcon;
                    }, 2000);
                });
            });
        }

        // Render custom fields based on selected gateway
        function renderCustomFields() {
            customFieldsContainer.innerHTML = '';

            if (!customFields || customFields.length === 0) {
                return;
            }

            customFields.forEach((field, index) => {
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'space-y-1';

                const label = document.createElement('label');
                label.className = 'block text-sm font-medium text-gray-700';
                label.textContent = field.label;
                if (field.required) {
                    const required = document.createElement('span');
                    required.className = 'text-red-500';
                    required.textContent = ' *';
                    label.appendChild(required);
                }

                fieldDiv.appendChild(label);

                let input;
                const fieldName = `custom_data[${field.label}]`;

                switch (field.type) {
                    case 'textarea':
                        input = document.createElement('textarea');
                        input.rows = 3;
                        break;
                    case 'select':
                        input = document.createElement('select');
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select...';
                        input.appendChild(defaultOption);

                        if (field.options) {
                            field.options.forEach(opt => {
                                const option = document.createElement('option');
                                option.value = opt;
                                option.textContent = opt;
                                input.appendChild(option);
                            });
                        }
                        break;
                    case 'checkbox':
                        const checkboxContainer = document.createElement('div');
                        checkboxContainer.className = 'space-y-2';
                        if (field.options) {
                            field.options.forEach(opt => {
                                const checkDiv = document.createElement('div');
                                checkDiv.className = 'flex items-center';
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = fieldName + '[]';
                                checkbox.value = opt;
                                checkbox.className = 'rounded border-gray-300 text-green-600 focus:ring-green-500';
                                const checkLabel = document.createElement('label');
                                checkLabel.className = 'ml-2 text-sm text-gray-700';
                                checkLabel.textContent = opt;
                                checkDiv.appendChild(checkbox);
                                checkDiv.appendChild(checkLabel);
                                checkboxContainer.appendChild(checkDiv);
                            });
                        }
                        fieldDiv.appendChild(checkboxContainer);
                        break;
                    case 'radio':
                        const radioContainer = document.createElement('div');
                        radioContainer.className = 'space-y-2';
                        if (field.options) {
                            field.options.forEach(opt => {
                                const radioDiv = document.createElement('div');
                                radioDiv.className = 'flex items-center';
                                const radio = document.createElement('input');
                                radio.type = 'radio';
                                radio.name = fieldName;
                                radio.value = opt;
                                radio.className = 'border-gray-300 text-green-600 focus:ring-green-500';
                                const radioLabel = document.createElement('label');
                                radioLabel.className = 'ml-2 text-sm text-gray-700';
                                radioLabel.textContent = opt;
                                radioDiv.appendChild(radio);
                                radioDiv.appendChild(radioLabel);
                                radioContainer.appendChild(radioDiv);
                            });
                        }
                        fieldDiv.appendChild(radioContainer);
                        break;
                    case 'file':
                        input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        input.className = 'block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100';
                        break;
                    default:
                        input = document.createElement('input');
                        input.type = 'text';
                }

                if (input && field.type !== 'checkbox' && field.type !== 'radio') {
                    input.name = fieldName;
                    if (field.required) {
                        input.required = true;
                    }
                    if (field.type !== 'file') {
                        input.className = 'w-full rounded-lg border-gray-200 focus:border-green-500 focus:ring-green-500';
                    }
                    input.placeholder = field.label;
                    fieldDiv.appendChild(input);
                }

                customFieldsContainer.appendChild(fieldDiv);
            });
        }

        // MAX button click handler
        maxBtn.addEventListener('click', function () {
            const availableBalance = {{ $availableBalance }};
            amountInput.value = availableBalance.toFixed(2);
            amountInput.focus();
            validateForm();
        });

        // Amount input
        amountInput.addEventListener('input', validateForm);

        function validateForm() {
            const amount = parseFloat(amountInput.value);
            const amountError = document.getElementById('amount-error');

            if (!selectedGateway) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400');
                amountError.textContent = '';
                return;
            }

            if (isNaN(amount) || amount <= 0) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400');
                amountError.textContent = 'Please enter a valid amount';
                return;
            }

            if (amount < minLimit) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400');
                amountError.textContent = `Minimum withdrawal is ${minLimit} ${document.getElementById('currency-label').textContent}`;
                return;
            }

            if (amount > maxLimit) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400');
                amountError.textContent = `Maximum withdrawal is ${maxLimit} ${document.getElementById('currency-label').textContent}`;
                return;
            }

            const availableBalance = {{ $availableBalance }};
            if (amount > availableBalance) {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400');
                amountError.textContent = `Insufficient balance. Available: ${availableBalance.toFixed(2)} USDT`;
                return;
            }

            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-400');
            submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            amountError.textContent = '';
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Check for pending orders before withdrawal
            try {
                const checkResponse = await fetch('{{ route('withdrawal.check-pending-orders') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    }
                });

                const checkData = await checkResponse.json();

                if (checkData.has_pending_orders) {
                    document.getElementById('errorMessage').textContent = 'You have pending orders. Please complete all orders before requesting withdrawal.';
                    errorModal.classList.remove('hidden');
                    errorModal.classList.add('flex');
                    return;
                }
            } catch (error) {
                console.error('Error checking pending orders:', error);
            }

            const formData = new FormData(form);

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