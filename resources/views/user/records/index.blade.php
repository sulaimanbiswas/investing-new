@extends('layouts.user.app')

@section('title', 'Records - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">Records</h1>
    </div>

    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="mb-4">
            <p class="text-sm text-white/80">Overview</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Total Deposits</p>
                <p class="text-lg font-bold">{{ number_format($stats['total_deposits'], 2) }}</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Total Withdrawals</p>
                <p class="text-lg font-bold">{{ number_format($stats['total_withdrawals'], 2) }}</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Order Payments</p>
                <p class="text-lg font-bold">{{ number_format($stats['total_order_payments'], 2) }}</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Order Profits</p>
                <p class="text-lg font-bold text-emerald-200">{{ number_format($stats['total_order_profits'], 2) }}</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Principal Returns</p>
                <p class="text-lg font-bold">{{ number_format($stats['total_principal_returns'], 2) }}</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-lg px-3 py-2">
                <p class="text-white/80 text-xs mb-1">Net Balance</p>
                <p class="text-lg font-bold {{ $stats['net_balance'] >= 0 ? 'text-emerald-200' : 'text-rose-200' }}">
                    {{ $stats['net_balance'] >= 0 ? '+' : '' }}{{ number_format($stats['net_balance'], 2) }}
                </p>
            </div>
        </div>
    </div>

    @php
        $tabs = [
            // 'incomplete' => ['label' => 'Incomplete Orders', 'icon' => 'fa-clock', 'color' => 'orange'],
            'complete' => ['label' => 'Complete Orders', 'icon' => 'fa-check-circle', 'color' => 'green'],
            'transactions' => ['label' => 'Transactions', 'icon' => 'fa-exchange-alt', 'color' => 'blue'],
        ];
    @endphp

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="flex overflow-x-auto gap-2 p-2" style="scrollbar-width: thin;">
            @foreach($tabs as $key => $tab)
                <a href="{{ route('records.index', ['tab' => $key]) }}"
                    class="flex-shrink-0 whitespace-nowrap px-4 py-2 rounded-xl text-xs font-semibold transition {{ $activeTab === $key ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas {{ $tab['icon'] }} mr-1"></i>{{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Incomplete Orders Tab -->
    {{-- @if($activeTab === 'incomplete')
    @forelse($incompleteOrders as $order)
    <div class="bg-white rounded-xl shadow-md p-5 border-2 border-orange-300 mb-4">
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-1">Order #{{ $order->order_number }}</p>
            <p class="text-xs text-gray-500">{{ $order->type === 'combo' ? 'Combo Order' : 'Single Order' }}</p>
        </div>

        <!-- Products List -->
        @if($order->manage_crypto && count($order->manage_crypto) > 0)
        <div class="space-y-3 mb-4">
            @foreach($order->manage_crypto as $product)
            <div class="flex items-start gap-4 p-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                @if(!empty($product['image']))
                <div class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden">
                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] ?? 'Product' }}"
                        class="w-full h-full object-cover">
                </div>
                @else
                <div
                    class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
                @endif
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900 mb-1">{{ $product['name'] ?? 'Product' }}</h4>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-700">Price:
                            <strong>${{ number_format($product['price'] ?? 0, 2) }}</strong></span>
                        <span class="text-gray-700">x <strong>{{ $product['quantity'] ?? 0 }}</strong></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Order Summary -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 space-y-2 mb-4 border-2 border-blue-200">
            <div class="flex justify-between text-sm">
                <span class="text-gray-700 font-medium">Order Amount:</span>
                <span class="font-bold text-gray-900">${{ number_format($order->order_amount, 2) }} USDT</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-700 font-medium">Commission:</span>
                <span class="font-bold text-green-600">${{ number_format($order->profit_amount, 2) }} USDT</span>
            </div>
            <div class="flex justify-between border-t-2 border-blue-300 pt-2">
                <span class="font-bold text-gray-900">Expected Income:</span>
                <span class="font-bold text-orange-500 text-xl">${{ number_format($order->order_amount +
                    $order->profit_amount, 2) }}
                    USDT</span>
            </div>
        </div>

        <button type="button"
            class="submit-order-btn w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-4 rounded-xl shadow-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2"
            data-order-id="{{ $order->id }}">
            <i class="fas fa-check-circle text-xl"></i>
            <span>Submit Order</span>
        </button>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm px-4 py-12 text-center">
        <i class="fas fa-check-circle text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm">No incomplete orders found.</p>
    </div>
    @endforelse

    @if($incompleteOrders->hasPages())
    <div class="mt-4">
        {{ $incompleteOrders->links() }}
    </div>
    @endif
    @endif --}}

    <!-- Complete Orders Tab -->
    @if($activeTab === 'complete')
        @forelse($completeOrders as $order)
            <div class="bg-white rounded-xl shadow-md p-5 mb-4 border-2 border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Order #{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500">{{ $order->type === 'combo' ? 'Combo Order' : 'Single Order' }}</p>
                    </div>
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i>Completed
                    </span>
                </div>

                <!-- Products List -->
                @if($order->manage_crypto && count($order->manage_crypto) > 0)
                    <div class="space-y-2 mb-4">
                        @foreach($order->manage_crypto as $product)
                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                @if(!empty($product['image']))
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden">
                                        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] ?? 'Product' }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-white text-sm"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $product['name'] ?? 'Product' }}</h4>
                                    <p class="text-xs text-gray-600">
                                        ${{ number_format($product['price'] ?? 0, 2) }} x {{ $product['quantity'] ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Order Summary -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 space-y-2 border-2 border-green-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Order Amount:</span>
                        <span class="font-bold text-gray-900">${{ number_format($order->order_amount, 2) }} USDT</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Commission:</span>
                        <span class="font-bold text-green-600">${{ number_format($order->profit_amount, 2) }} USDT</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-green-300 pt-2">
                        <span class="font-bold text-gray-900">Total Income:</span>
                        <span
                            class="font-bold text-green-600 text-xl">${{ number_format($order->order_amount + $order->profit_amount, 2) }}
                            USDT</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 pt-2 border-t border-green-200">
                        <span>Completed at:</span>
                        <span>{{ $order->paid_at ? $order->paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm px-4 py-12 text-center">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No completed orders found.</p>
            </div>
        @endforelse

        @if($completeOrders->hasPages())
            <div class="mt-4">
                {{ $completeOrders->links() }}
            </div>
        @endif
    @endif

    <!-- Transactions Tab -->
    @if($activeTab === 'transactions')
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">All Transactions</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($transactions as $txn)
                    @php
                        $isPositive = $txn->amount >= 0;
                        $typeConfig = [
                            'deposit' => ['icon' => 'fa-arrow-down', 'color' => 'blue', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                            'withdrawal' => ['icon' => 'fa-arrow-up', 'color' => 'rose', 'bg' => 'bg-rose-50', 'text' => 'text-rose-600'],
                            'order_payment' => ['icon' => 'fa-shopping-cart', 'color' => 'red', 'bg' => 'bg-red-50', 'text' => 'text-red-600'],
                            'order_principal_return' => ['icon' => 'fa-undo', 'color' => 'indigo', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
                            'order_profit' => ['icon' => 'fa-coins', 'color' => 'emerald', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                        ];
                        $config = $typeConfig[$txn->type] ?? ['icon' => 'fa-circle', 'color' => 'gray', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                    @endphp
                    <div class="px-4 py-3 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 {{ $config['bg'] }} rounded-full flex items-center justify-center">
                                <i class="fas {{ $config['icon'] }} {{ $config['text'] }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">
                                            {{ ucwords(str_replace('_', ' ', $txn->type)) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $txn->remarks }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="font-bold text-sm {{ $isPositive ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $isPositive ? '+' : '' }}{{ number_format($txn->amount, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $txn->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 mt-2 text-xs text-gray-600">
                                    <span>Before: <strong>{{ number_format($txn->balance_before, 2) }}</strong></span>
                                    <span class="text-gray-300">→</span>
                                    <span>After: <strong
                                            class="text-emerald-600">{{ number_format($txn->balance_after, 2) }}</strong></span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $txn->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No transactions found.</p>
                    </div>
                @endforelse
            </div>
            @if($transactions->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Submit existing unpaid orders
        document.querySelectorAll('.submit-order-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const orderId = this.dataset.orderId;
                const btn = this;

                Swal.fire({
                    title: 'Submit Order?',
                    text: 'Are you sure you want to submit this order?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Submit',
                    cancelButtonText: 'Cancel'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                        try {
                            const response = await fetch(`/menu/order/${orderId}/submit`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    html: `<p>${data.message}</p><p class="text-lg font-bold text-green-600 mt-2">New Balance: $${data.new_balance}</p>`,
                                    icon: 'success',
                                    confirmButtonColor: '#10b981'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                if (data.message && data.message.includes('Insufficient balance')) {
                                    Swal.fire({
                                        title: 'Insufficient Balance',
                                        text: data.message,
                                        icon: 'error',
                                        confirmButtonColor: '#dc2626',
                                        showCancelButton: true,
                                        confirmButtonText: 'Deposit Now',
                                        cancelButtonText: 'Close'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = '{{ route('deposit') }}';
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: data.message,
                                        icon: 'error',
                                        confirmButtonColor: '#dc2626'
                                    });
                                }
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fas fa-check-circle text-xl"></i><span>Submit Order</span>';
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#dc2626'
                            });
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check-circle text-xl"></i><span>Submit Order</span>';
                        }
                    }
                });
            });
        });
    </script>
@endpush