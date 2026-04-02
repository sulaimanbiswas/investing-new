@extends('layouts.user.app')

@section('title', __('ui.deposit') . ' - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.deposit') }}</h1>
    </div>

    @if(isset($pendingDeposit) && $pendingDeposit)
        <!-- Pending Deposit Modal -->
        <div id="pendingDepositModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full text-center relative">
                <h2 class="text-xl font-bold text-red-600 mb-4">{{ __('ui.pending_deposit_exists') }}</h2>
                <p class="mb-4 text-gray-700">{{ __('ui.pending_deposit_warning') }}</p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4 text-left">
                    <div class="mb-2"><span class="font-semibold">{{ __('ui.amount') }}:</span>
                        {{ number_format($pendingDeposit->amount, 2) }}
                        {{ $pendingDeposit->currency }}
                    </div>
                    <div class="mb-2"><span class="font-semibold">{{ __('ui.gateway') }}:</span>
                        {{ $pendingDeposit->gateway->name ?? '' }}
                    </div>
                    <div class="mb-2"><span class="font-semibold">{{ __('ui.order_number_label') }}:</span>
                        {{ $pendingDeposit->order_number }}</div>
                    <div><span class="font-semibold">{{ __('ui.status') }}:</span> <span
                            class="text-orange-600 font-semibold">{{ __('ui.pending') }}</span>
                    </div>
                </div>
                <button id="pendingDepositBackBtn"
                    class="mt-4 w-full bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition">{{ __('ui.ok') }}
                </button>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('pendingDepositBackBtn').onclick = function () {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = '/';
                    }
                };
            });
        </script>
    @else
        <!-- Payment Method Selection -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
            <h3 class="text-sm font-medium text-gray-700 mb-3">{{ __('ui.payment_method') }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @forelse($gateways as $gateway)
                    <label class="payment-method-card cursor-pointer">
                        <input type="radio" name="gateway" value="{{ $gateway->id }}" class="hidden gateway-radio"
                            data-min="{{ $gateway->min_limit }}" data-max="{{ $gateway->max_limit }}"
                            data-currency="{{ $gateway->currency }}">
                        <div
                            class="border-2 border-gray-200 rounded-xl p-4 text-center transition hover:border-indigo-400 hover:bg-indigo-50">
                            @if($gateway->logo_path)
                                <img src="{{ asset($gateway->logo_path) }}" alt="{{ $gateway->name }}"
                                    class="w-12 h-12 mx-auto mb-2 object-contain">
                            @else
                                <div
                                    class="w-12 h-12 mx-auto mb-2 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            @endif
                            <div class="font-semibold text-gray-800 text-sm">{{ $gateway->name }}</div>
                        </div>
                    </label>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                        <p>{{ __('ui.no_payment_methods_available') }}</p>
                    </div>
                @endforelse
            </div>
            <p class="text-xs text-gray-500 mt-3" id="gateway-error"></p>
        </div>

        <!-- Deposit Amount -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-5">
            <h3 class="text-sm font-medium text-gray-700 mb-3">{{ __('ui.deposit_amount') }}</h3>
            <div
                class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden focus-within:border-indigo-500 transition">
                <span class="px-4 py-3 bg-gray-50 text-gray-700 font-semibold" id="currency-label">USDT</span>
                <input type="number" id="amount" name="amount" placeholder="{{ __('ui.enter_amount') }}" min="0" step="0.01"
                    class="flex-1 px-4 py-3 border-0 focus:ring-0 focus:outline-none">
            </div>
            <p class="text-xs text-gray-500 mt-2" id="amount-hint">{{ __('ui.select_payment_method_first') }}</p>
            <p class="text-xs text-red-600 mt-1" id="amount-error"></p>
        </div>

        <!-- Deposit Button -->
        <button type="button" id="deposit-btn" disabled
            class="w-full bg-gray-400 text-white font-semibold py-4 rounded-xl transition disabled:cursor-not-allowed">
            {{ __('ui.deposit_now') }}
        </button>
        <script>
            document.getElementById('deposit-btn').addEventListener('click', function () {
                if (this.disabled) return;
                const gateway = document.querySelector('input[name="gateway"]:checked').value;
                const amount = document.getElementById('amount').value;
                const btn = this;
                btn.disabled = true;
                btn.textContent = '{{ __('ui.processing') }}';
                fetch('{{ route('deposit.create-initialed') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ gateway, amount })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            btn.disabled = false;
                            btn.textContent = '{{ __('ui.deposit_now') }}';
                            alert(data.message || '{{ __('ui.failed_create_deposit') }}');
                        }
                    })
                    .catch(error => {
                        btn.disabled = false;
                        btn.textContent = '{{ __('ui.deposit_now') }}';
                        alert('{{ __('ui.error_try_again') }}');
                    });
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script>
        let selectedGateway = null;
        let minLimit = 0;
        let maxLimit = 0;

        // Gateway selection
        document.querySelectorAll('.gateway-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                selectedGateway = this.value;
                minLimit = parseFloat(this.dataset.min);
                maxLimit = parseFloat(this.dataset.max);
                const currency = this.dataset.currency;

                // Update UI
                document.querySelectorAll('.payment-method-card > div').forEach(card => {
                    card.classList.remove('border-indigo-500', 'bg-indigo-50');
                    card.classList.add('border-gray-200');
                });
                this.parentElement.querySelector('div').classList.remove('border-gray-200');
                this.parentElement.querySelector('div').classList.add('border-indigo-500', 'bg-indigo-50');

                document.getElementById('currency-label').textContent = currency;
                document.getElementById('amount-hint').textContent = `Min: ${minLimit} ${currency} | Max: ${maxLimit} ${currency}`;
                document.getElementById('gateway-error').textContent = '';

                validateForm();
            });
        });

        // Amount input
        document.getElementById('amount').addEventListener('input', validateForm);

        function validateForm() {
            const amount = parseFloat(document.getElementById('amount').value);
            const btn = document.getElementById('deposit-btn');
            const amountError = document.getElementById('amount-error');

            if (!selectedGateway) {
                btn.disabled = true;
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-gray-400');
                amountError.textContent = '';
                return;
            }

            if (isNaN(amount) || amount <= 0) {
                btn.disabled = true;
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-gray-400');
                amountError.textContent = '{{ __('ui.invalid_amount') }}';
                return;
            }

            if (amount < minLimit) {
                btn.disabled = true;
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-gray-400');
                amountError.textContent = `Minimum deposit is ${minLimit} ${document.getElementById('currency-label').textContent}`;
                return;
            }

            if (amount > maxLimit) {
                btn.disabled = true;
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-gray-400');
                amountError.textContent = `Maximum deposit is ${maxLimit} ${document.getElementById('currency-label').textContent}`;
                return;
            }

            btn.disabled = false;
            btn.classList.remove('bg-gray-400');
            btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            amountError.textContent = '';
        }

        // Deposit button click
        document.getElementById('deposit-btn').addEventListener('click', function () {
            if (this.disabled) return;

            const amount = document.getElementById('amount').value;

            window.location.href = `/deposit/confirm?gateway=${selectedGateway}&amount=${amount}`;
        });
    </script>
@endpush