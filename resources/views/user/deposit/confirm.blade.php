@extends('layouts.user.app')

@section('title', __('ui.deposit_confirm') . ' - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.deposit_confirm') }}</h1>
    </div>

    <!-- Confirmation Message -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5 text-center">
        <p class="text-gray-700">{{ __('ui.you_have_requested') }} <span
                class="font-bold text-indigo-600">{{ number_format($deposit->amount, 2) }}
                {{ $gateway->currency }}</span>. {{ __('ui.please_pay') }} <span
                class="font-bold text-green-600">{{ number_format($deposit->amount, 2) }} {{ $gateway->currency }}</span>
            for
            {{ __('ui.payment_success_hint') }}
        </p>
    </div>

    <!-- QR Code -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-5">
        <div class="flex justify-center mb-4">
            @if($gateway->qr_path && file_exists(public_path(ltrim($gateway->qr_path, '/'))))
                <img src="{{ asset($gateway->qr_path) }}" alt="QR Code" class="w-64 h-64 border-4 border-gray-800 rounded-lg">
            @else
                <div class="w-64 h-64 border-4 border-gray-800 rounded-lg bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-qrcode text-gray-400 text-6xl"></i>
                </div>
            @endif
        </div>

        <!-- Address -->
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between gap-3">
                <p class="text-base text-gray-700 break-all font-mono">{{ $gateway->address }}</p>
                <button id="copyBtn" onclick="copyAddress(this)" class="text-indigo-600 hover:text-indigo-700 transition">
                    <i id="copyIcon" class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Tips -->
        <div class="space-y-2 text-sm">
            <p class="font-semibold text-gray-800">{{ __('ui.tips') }}</p>
            <ol class="list-decimal list-inside space-y-2 text-gray-600">
                <li>{{ __('ui.deposit_tip_1') }}</li>
                <li>{{ __('ui.deposit_tip_2') }} <span class="font-semibold">{{ number_format($gateway->min_limit, 2) }}
                        {{ $gateway->currency }}</span>.
                </li>
                <li>{{ __('ui.deposit_tip_3') }}</li>
            </ol>
        </div>
    </div>

    <!-- Transaction Details Form -->
    @if($gateway->requires_txn_id || $gateway->requires_screenshot)
        <form id="depositForm" class="space-y-5">
            @csrf
            <input type="hidden" name="deposit_id" value="{{ $deposit->id }}">

            @if($gateway->requires_txn_id)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.transaction_id') }} <span
                            class="text-red-600">*</span></label>
                    <input type="text" name="txn_id" id="txn_id" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none transition"
                        placeholder="{{ __('ui.enter_transaction_id') }}">
                    <p class="text-xs text-gray-500 mt-2">{{ __('ui.enter_transaction_id_hint') }}</p>
                </div>
            @endif

            @if($gateway->requires_screenshot)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.payment_screenshot') }} <span
                            class="text-red-600">*</span></label>
                    <input type="file" name="screenshot" id="screenshot" accept="image/*" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none transition">
                    <p class="text-xs text-gray-500 mt-2">{{ __('ui.upload_payment_screenshot_hint') }}</p>
                </div>
            @endif

            <button type="submit" id="submitBtn"
                class="w-full bg-indigo-600 text-white font-semibold py-4 rounded-xl hover:bg-indigo-700 active:scale-95 transition">
                {{ __('ui.confirm_payment') }}
            </button>
        </form>
    @else
        <!-- No verification required -->
        <form id="depositForm" class="space-y-5">
            @csrf
            <input type="hidden" name="deposit_id" value="{{ $deposit->id }}">

            {{-- <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ __('ui.no_verification_required') }}</h2>
                <p class="text-gray-600 mb-4">Your payment will be confirmed automatically. Click the button below to proceed.
                </p>
            </div> --}}

            <button type="submit" id="submitBtn"
                class="w-full bg-green-600 text-white font-semibold py-4 rounded-xl hover:bg-green-700 active:scale-95 transition flex items-center justify-center gap-2">
                <i class="fas fa-check"></i>
                {{ __('ui.pay_now') }}
            </button>
        </form>
    @endif

@endsection

@push('scripts')
    <script>
        function copyAddress(btn) {
            const address = '{{ $gateway->address }}';
            const icon = document.getElementById('copyIcon');

            navigator.clipboard.writeText(address).then(() => {
                // Change icon to checkmark
                icon.className = 'fas fa-clipboard-check';
                btn.classList.remove('text-indigo-600', 'hover:text-indigo-700');
                btn.classList.add('text-green-600');

                // Reset after 5 seconds
                setTimeout(() => {
                    icon.className = 'fas fa-copy';
                    btn.classList.remove('text-green-600');
                    btn.classList.add('text-indigo-600', 'hover:text-indigo-700');
                }, 5000);
            });
        }

        // Always handle form submission
        document.getElementById('depositForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = '{{ __('ui.submitting') }}';

            // If any file input exists, use FormData, else use JSON
            const txnId = document.getElementById('txn_id')?.value;
            const screenshotInput = document.getElementById('screenshot');
            const hasFile = screenshotInput && screenshotInput.files.length > 0;

            if (hasFile) {
                const formData = new FormData(this);
                fetch('{{ route("deposit.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => handleDepositResponse(data, submitBtn))
                    .catch(error => handleDepositError(error, submitBtn));
            } else {
                // No file, send as JSON
                fetch('{{ route("deposit.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        deposit_id: {{ $deposit->id }},
                        txn_id: txnId
                    })
                })
                    .then(response => response.json())
                    .then(data => handleDepositResponse(data, submitBtn))
                    .catch(error => handleDepositError(error, submitBtn));
            }
        });

        function handleDepositResponse(data, submitBtn) {
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                flasher.error(data.message || '{{ __('ui.failed_process_deposit') }}');
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __('ui.confirm_payment') }}';
            }
        }

        function handleDepositError(error, submitBtn) {
            flasher.error('{{ __('ui.error_try_again') }}');
            console.error(error);
            submitBtn.disabled = false;
            submitBtn.textContent = '{{ __('ui.confirm_payment') }}';
        }
    </script>
@endpush