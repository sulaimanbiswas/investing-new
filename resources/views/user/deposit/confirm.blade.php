@extends('layouts.user.app')

@section('title', 'Deposit Confirm - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">Deposit Confirm</h1>
    </div>

    <!-- Confirmation Message -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5 text-center">
        <p class="text-gray-700">You have requested <span
                class="font-bold text-indigo-600">{{ number_format($deposit->amount, 2) }}
                {{ $gateway->currency }}</span>. Please pay <span
                class="font-bold text-green-600">{{ number_format($deposit->amount, 2) }} {{ $gateway->currency }}</span>
            for
            successful payment</p>
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
            <p class="font-semibold text-gray-800">Tips:</p>
            <ol class="list-decimal list-inside space-y-2 text-gray-600">
                <li>The recharge address is a <span class="text-red-600 font-semibold">one-time address</span>, please do
                    not use it or transfer it repeatedly.</li>
                <li>The minimum recharge amount is subject to the actual transfer amount, not less than <span
                        class="font-semibold">{{ number_format($gateway->min_limit, 2) }} {{ $gateway->currency }}</span>.
                </li>
                <li>After recharging, it will take about <span class="text-indigo-600 font-semibold">2 minutes</span> to
                    confirm the payment. Please wait patiently.</li>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID <span
                            class="text-red-600">*</span></label>
                    <input type="text" name="txn_id" id="txn_id" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none transition"
                        placeholder="Enter your transaction ID">
                    <p class="text-xs text-gray-500 mt-2">Please enter the transaction ID from your payment</p>
                </div>
            @endif

            @if($gateway->requires_screenshot)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Screenshot <span
                            class="text-red-600">*</span></label>
                    <input type="file" name="screenshot" id="screenshot" accept="image/*" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:outline-none transition">
                    <p class="text-xs text-gray-500 mt-2">Upload a clear screenshot of your payment confirmation</p>
                </div>
            @endif

            <button type="submit" id="submitBtn"
                class="w-full bg-indigo-600 text-white font-semibold py-4 rounded-xl hover:bg-indigo-700 transition">
                Submit Payment Proof
            </button>
        </form>
    @else
        <!-- Pay Now Button (No requirements) -->
        <button type="button" onclick="confirmPayment()"
            class="w-full bg-indigo-600 text-white font-semibold py-4 rounded-xl hover:bg-indigo-700 transition">
            Pay Now
        </button>
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

        @if($gateway->requires_txn_id || $gateway->requires_screenshot)
            // Handle form submission with transaction details
            document.getElementById('depositForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';

                const formData = new FormData(this);

                fetch('{{ route("deposit.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            flasher.error(data.message || 'Failed to process deposit');
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Submit Payment Proof';
                        }
                    })
                    .catch(error => {
                        flasher.error('An error occurred. Please try again.');
                        console.error(error);
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Payment Proof';
                    });
            });
        @else
            function confirmPayment() {
                // Update deposit to pending (no requirements)
                fetch('{{ route("deposit.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        deposit_id: {{ $deposit->id }}
                                                        })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            flasher.error(data.message || 'Failed to process deposit');
                        }
                    })
                    .catch(error => {
                        flasher.error('An error occurred. Please try again.');
                        console.error(error);
                    });
            }
        @endif
    </script>
@endpush