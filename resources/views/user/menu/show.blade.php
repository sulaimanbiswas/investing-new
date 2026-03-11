@extends('layouts.user.app')

@section('title', $platform->name . ' - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('menu.index') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Platform Details</h1>
    </div>

    <div
        class="grid grid-cols-12 items-center gap-4 mb-6 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-xl overflow-hidden">

        <!-- Platform Name Header -->
        <div class="col-span-5 md:col-span-6 text-center">
            <div class="p-3 md:p-6">
                <div class="">
                    @if($platform->image)
                        <img src="{{ asset($platform->image) }}" alt="{{ $platform->name }}"
                            class="w-10 md:w-16 h-10 md:h-16 rounded-xl object-cover shadow-xl mx-auto border-4 border-white">
                    @else
                        <div
                            class="w-10 h-10  bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-xl mx-auto border-2 border-white">
                            <i class="fas fa-store text-white text-2xl"></i>
                        </div>
                    @endif
                </div>
                <h2 class="text-base font-bold text-white ">
                    {{ $platform->name }}
                </h2>
                <p class="text-gray-100 text-sm">Commission Rate: <span
                        class="font-bold text-green-200">{{ number_format($platform->commission, 1) }}%</span></p>
            </div>
        </div>

        <!-- Account Balance Card -->
        <div class="col-span-7 md:col-span-6">
            <div class="px-3 md:p-6 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                <div class="relative">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-wallet text-white text-xl"></i>
                        <p class="text-white text-sm opacity-90">Account Balance</p>
                    </div>
                    <p class="text-white text-3xl font-bold mb-1">{{ number_format($user->balance, 2) }}</p>
                    <p class="text-white text-lg opacity-90">USDT</p>
                </div>
            </div>
        </div>

    </div>


    <!-- Unpaid Order (Single) -->
    @if($unpaidOrder && $packageName == $userVipLevel && $hasVipLevel && !$hasReachedDailyLimit)
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Your Current Order</h3>
            <div class="bg-white rounded-xl shadow-md p-5 border-2 border-orange-300">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order #{{ $unpaidOrder->order_number }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $unpaidOrder->type === 'combo' ? 'Combo Order' : 'Single Order' }}
                            </p>
                        </div>
                        {{-- @if($unpaidOrder->userOrderSet && $unpaidOrder->userOrderSet->orderSet &&
                        $unpaidOrder->userOrderSet->orderSet->platform)
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                            {{ $unpaidOrder->userOrderSet->orderSet->platform->name }}
                        </span>
                        @endif --}}
                    </div>
                </div>

                <!-- Products List -->
                @if($unpaidOrder->manage_crypto && count($unpaidOrder->manage_crypto) > 0)
                    <div class="space-y-3 mb-4">
                        @foreach($unpaidOrder->manage_crypto as $product)
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
                        <span class="font-bold text-gray-900">${{ number_format($unpaidOrder->order_amount, 2) }} USDT</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Commission:</span>
                        <span class="font-bold text-green-600">${{ number_format($unpaidOrder->profit_amount, 2) }} USDT</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-blue-300 pt-2">
                        <span class="font-bold text-gray-900">Expected Income:</span>
                        <span
                            class="font-bold text-orange-500 text-xl">${{ number_format($unpaidOrder->order_amount + $unpaidOrder->profit_amount, 2) }}
                            USDT</span>
                    </div>
                </div>

                <button type="button"
                    class="submit-order-btn w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-4 rounded-xl shadow-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2"
                    data-order-id="{{ $unpaidOrder->id }}">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span>Submit Order</span>
                </button>
            </div>
        </div>
    @endif

    <div class="mb-6">
        @if(!$hasVipLevel)
            <div
                class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white font-bold py-6 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-3">
                <i class="fas fa-wallet text-4xl mb-2"></i>
                <p class="text-xl font-bold">Not in Level Level</p>
                <p class="text-sm opacity-90 text-center px-4">You are not in any Level level. Deposit and start earning!</p>
                <a href="{{ route('deposit') }}"
                    class="mt-2 bg-white text-indigo-600 font-bold py-3 px-6 rounded-xl hover:bg-gray-100 active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i>Deposit Now
                </a>
            </div>
        @elseif($packageName != $userVipLevel && $hasVipLevel)
                <div
                    class="w-full bg-gradient-to-r from-red-500 to-yellow-500 text-white font-bold py-6 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-3">
                    <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                    <p class="text-xl font-bold">
                        You are not eligible for this platform
                    </p>
                    <p class="text-sm opacity-90 text-center px-4">
                        You are not eligible for this platform. Your current Level level is <span
                            class="font-bold">{{ $userVipLevel }}</span>.<br>
                        Please upgrade to <span class="font-bold">{{ $platform->package_name }}</span> to access and complete orders
                        here.
                    </p>
                    <div class="mt-2 bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <p class="text-xs font-medium">
                            Goto {{ $userVipLevel }}
                        </p>

                    </div>
                </div>
            </div>
        @elseif($hasReachedDailyLimit)
        <div
            class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold py-6 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-3">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <p class="text-xl font-bold">All Orders Completed!</p>
            <p class="text-sm opacity-90 text-center px-4">Your daily limit has been reached. You can complete more orders
                tomorrow.</p>
            <div class="mt-2 bg-white bg-opacity-20 rounded-lg px-4 py-2">
                <p class="text-xs font-medium">{{ $todayCompletedCount }}/{{ $user->daily_order_limit }} orders completed
                    today</p>
            </div>
        </div>
    @elseif($unpaidOrder)
        <div
            class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-5 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-2">
            <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
            <p class="text-lg">Complete Your Unpaid Order</p>
            <p class="text-sm opacity-90">Please submit your pending order above</p>
        </div>
    @elseif(!$unpaidOrder && !$hasReachedDailyLimit)
        <div
            class="w-full bg-gradient-to-r from-teal-400 to-green-500 text-white font-bold py-6 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-3">
            <i class="fas fa-check-circle text-4xl mb-2"></i>
            <p class="text-xl font-bold">All Orders Completed!</p>
            <p class="text-sm opacity-90 text-center px-4">
                You have completed all your orders for today.
            </p>
            <div class="mt-2 bg-white bg-opacity-20 rounded-lg px-4 py-2">
                <p class="text-xs font-medium">
                    Contact support for more orders.
                </p>
            </div>
        </div>
    @else
        <div
            class="w-full bg-gradient-to-r from-gray-500 to-gray-700 text-white font-bold py-5 rounded-2xl shadow-xl flex flex-col items-center justify-center gap-2">
            <i class="fas fa-info-circle text-2xl mb-1"></i>
            <p class="text-lg">You are in {{ $userVipLevel }}</p>
            <p class="text-sm opacity-90">Complete orders to earn commission</p>
        </div>
    @endif
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <!-- Today's Time (Orders Count) -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-1">{{ $todayOrdersCount }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Today's Time</p>
            </div>
        </div>

        <!-- Today's Commission -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-coins text-green-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-green-600 mb-1">{{ number_format($todayCommission, 2) }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Today's Commission</p>
            </div>
        </div>

        <!-- Cash Gap Between Task -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-orange-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-orange-600 mb-1">{{ number_format($cashGap, 2) }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Cash gap between task</p>
            </div>
        </div>

        <!-- Yesterday's Buy Commission -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-purple-600 mb-1">{{ number_format($yesterdayCommission, 2) }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Yesterday's buy commission</p>
            </div>
        </div>

        <!-- Yesterday's Team Commission -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-indigo-600 mb-1">{{ number_format($yesterdayTeamCommission, 2) }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Yesterday's team commission</p>
            </div>
        </div>

        <!-- Money Frozen in Account -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-5">
            <div class="flex flex-col items-center justify-center h-full">
                <div class="bg-red-100 rounded-full w-12 h-12 flex items-center justify-center mb-3">
                    <i class="fas fa-lock text-red-600 text-xl"></i>
                </div>
                <p class="text-2xl font-bold text-red-600 mb-1">{{ number_format($user->freeze_amount, 2) }}</p>
                <p class="text-gray-600 text-xs text-center font-medium">Money frozen in account</p>
            </div>
        </div>
    </div>


    <!-- Hint Section -->
    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-md p-5 border-l-4 border-orange-500">
        <div class="flex items-start gap-3 mb-3">
            <div class="bg-orange-500 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-lightbulb text-white"></i>
            </div>
            <h3 class="font-bold text-gray-900 text-lg">Hint:</h3>
        </div>
        <div class="space-y-3 text-sm text-gray-700 ml-11">
            <p class="leading-relaxed flex items-start gap-2">
                <span class="font-bold text-red-600 flex-shrink-0">1:</span>
                <span><span class="font-bold text-green-600">{{ number_format($platform->commission, 1) }}%</span> of
                    the
                    amount of completed transaction earned.</span>
            </p>
            <p class="leading-relaxed flex items-start gap-2">
                <span class="font-bold text-red-600 flex-shrink-0">2:</span>
                <span>The system send task randomly. Complete them as soon as possible after match them, so as you avoid
                    hanging all the time.</span>
            </p>
        </div>
    </div>

@endsection

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full transform transition-all">
        <div class="p-6">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-orange-500 text-3xl"></i>
                </div>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Confirm Submission</h3>

            <!-- Message -->
            <p class="text-gray-600 text-center mb-6">Are you sure you want to submit this order?</p>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" id="cancelConfirmBtn"
                    class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                    Cancel
                </button>
                <button type="button" id="proceedConfirmBtn"
                    class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-xl hover:from-green-600 hover:to-green-700 active:scale-95 transition-all duration-200">
                    Confirm
                </button>
            </div>
        </div>
    </div>
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
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Success!</h3>

            <!-- Message -->
            <p id="successMessage" class="text-gray-600 text-center mb-2"></p>
            <p id="newBalanceText" class="text-lg font-bold text-green-600 text-center mb-6"></p>

            <!-- Button -->
            <button type="button" id="closeSuccessBtn"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-xl hover:from-green-600 hover:to-green-700 active:scale-95 transition-all duration-200">
                Continue
            </button>
        </div>
    </div>
</div>

<!-- Insufficient Balance Modal -->
<div id="insufficientBalanceModal"
    class="fixed inset-0 bg-black bg-opacity-60 z-[60] top-24 hidden items-start justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all">
        <div class="p-6">
            <!-- Warning Icon -->
            <div class="flex justify-center mb-4">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                </div>
            </div>

            <!-- Title -->
            <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Insufficient Balance</h3>

            <!-- Order Summary -->
            @if($unpaidOrder)
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 space-y-2 mb-4 border-2 border-blue-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Order Amount:</span>
                        <span class="font-bold text-gray-900">${{ number_format($unpaidOrder->order_amount, 2) }}
                            USDT</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Commission:</span>
                        <span class="font-bold text-green-600">${{ number_format($unpaidOrder->profit_amount, 2) }}
                            USDT</span>
                    </div>
                    <div class="flex justify-between border-t-2 border-blue-300 pt-2">
                        <span class="font-bold text-gray-900">Expected Income:</span>
                        <span
                            class="font-bold text-orange-500 text-xl">${{ number_format($unpaidOrder->order_amount + $unpaidOrder->profit_amount, 2) }}
                            USDT</span>
                    </div>
                </div>
            @endif

            <!-- Message -->
            <p id="insufficientBalanceMessage" class="text-gray-600 text-center mb-6"></p>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('deposit') }}"
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 rounded-xl hover:from-green-600 hover:to-green-700 active:scale-95 transition-all duration-200 text-center">
                    <i class="fas fa-plus-circle mr-2"></i>Deposit Now
                </a>
                <button type="button" id="closeInsufficientBalanceBtn"
                    class="w-full bg-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-300 active:scale-95 transition-all duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden  items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div
            class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-3xl">
            <h3 class="text-xl font-bold text-gray-900">Successful Order</h3>
            <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Order Number -->
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-1">Order No:</p>
                <p id="orderNumber" class="text-2xl font-bold text-red-600"></p>
            </div>

            <!-- Products List -->
            <div id="productsList" class="space-y-4 mb-6">
                <!-- Products will be dynamically inserted here -->
            </div>

            <!-- Transaction Details -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Transaction Time</span>
                    <span id="transactionTime" class="text-gray-900 font-semibold text-sm"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Order Amount</span>
                    <span id="orderAmount" class="text-gray-900 font-bold"></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Commission</span>
                    <span id="commission" class="text-green-600 font-bold"></span>
                </div>
                <div class="flex items-center justify-between border-t border-gray-300 pt-3">
                    <span class="text-gray-900 font-bold text-lg">Expected Income</span>
                    <span id="expectedIncome" class="text-orange-500 font-bold text-xl"></span>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="button" id="submitOrderBtn"
                class="w-full mt-6 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-4 rounded-2xl shadow-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200">
                Submit Order
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentOrder = null;
        let currentButton = null;
        const modal = document.getElementById('orderModal');
        const confirmModal = document.getElementById('confirmModal');
        const successModal = document.getElementById('successModal');
        const insufficientBalanceModal = document.getElementById('insufficientBalanceModal');
        const grabBtn = document.getElementById('grabOrderBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const submitBtn = document.getElementById('submitOrderBtn');
        const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
        const proceedConfirmBtn = document.getElementById('proceedConfirmBtn');
        const closeSuccessBtn = document.getElementById('closeSuccessBtn');
        const closeInsufficientBalanceBtn = document.getElementById('closeInsufficientBalanceBtn');

        // Submit existing unpaid orders
        document.querySelectorAll('.submit-order-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                currentButton = this;
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            });
        });

        // Cancel confirmation
        if (cancelConfirmBtn) {
            cancelConfirmBtn.addEventListener('click', function () {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                currentButton = null;
            });
        }

        // Proceed with submission
        if (proceedConfirmBtn) {
            proceedConfirmBtn.addEventListener('click', async function () {
                if (!currentButton) return;

                const orderId = currentButton.dataset.orderId;

                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');

                currentButton.disabled = true;
                currentButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

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
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('newBalanceText').textContent = 'New Balance: $' + data.new_balance;
                        successModal.classList.remove('hidden');
                        successModal.classList.add('flex');
                    } else {
                        // Check if it's an insufficient balance error
                        if (data.message && data.message.includes('Insufficient balance')) {
                            document.getElementById('insufficientBalanceMessage').textContent = data.message;
                            insufficientBalanceModal.classList.remove('hidden');
                            insufficientBalanceModal.classList.add('flex');
                        } else {
                            alert(data.message);
                        }
                        currentButton.disabled = false;
                        currentButton.innerHTML = '<i class="fas fa-check-circle text-xl"></i><span>Submit Order</span>';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    currentButton.disabled = false;
                    currentButton.innerHTML = '<i class="fas fa-check-circle text-xl"></i><span>Submit Order</span>';
                }
            });
        }

        // Close success modal and reload
        if (closeSuccessBtn) {
            closeSuccessBtn.addEventListener('click', function () {
                successModal.classList.add('hidden');
                successModal.classList.remove('flex');
                location.reload();
            });
        }

        // Close insufficient balance modal
        if (closeInsufficientBalanceBtn) {
            closeInsufficientBalanceBtn.addEventListener('click', function () {
                insufficientBalanceModal.classList.add('hidden');
                insufficientBalanceModal.classList.remove('flex');
            });
        }

        // Grab Order
        if (grabBtn) {
            grabBtn.addEventListener('click', async function () {
                const platformId = this.dataset.platformId;
                const btn = this;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl"></i><span class="text-lg">Loading...</span>';

                try {
                    const response = await fetch(`/menu/platform/${platformId}/grab-order`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        currentOrder = data.order;
                        window.currentOrderId = data.order.id;
                        showOrderModal(data.order);
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-hand-holding-usd text-2xl"></i><span class="text-lg">Grab the order immediately</span>';
                }
            });
        }

        // Show Modal
        function showOrderModal(order) {
            document.getElementById('orderNumber').textContent = order.order_number;
            document.getElementById('transactionTime').textContent = new Date().toLocaleString();
            document.getElementById('orderAmount').textContent = order.order_amount + ' USDT';
            document.getElementById('commission').textContent = order.commission + ' USDT';
            document.getElementById('expectedIncome').textContent = order.expected_income + ' USDT';

            // Build products list
            const productsList = document.getElementById('productsList');
            productsList.innerHTML = '';

            if (order.manage_crypto && order.manage_crypto.length > 0) {
                order.manage_crypto.forEach(product => {
                    const productDiv = document.createElement('div');
                    productDiv.className = 'flex items-center gap-4 p-4 bg-white rounded-xl border-2 border-gray-200';
                    productDiv.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <i class="fas fa-box text-white text-2xl"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="flex-1">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <h4 class="font-bold text-gray-900 mb-1">${product.name}</h4>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <p class="text-sm text-gray-600">${product.price} x ${product.quantity}</p>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        `;
                    productsList.appendChild(productDiv);
                });
            }

            modal.classList.remove('hidden');
        }

        // Close Modal
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Submit Order from modal
        if (submitBtn) {
            submitBtn.addEventListener('click', async function () {
                if (!currentOrder) return;

                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                try {
                    const response = await fetch(`/menu/order/${window.currentOrderId}/submit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        modal.classList.add('hidden');
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('newBalanceText').textContent = 'New Balance: $' + data.new_balance;
                        successModal.classList.remove('hidden');
                        successModal.classList.add('flex');
                    } else {
                        modal.classList.add('hidden');
                        // Check if it's an insufficient balance error
                        if (data.message && data.message.includes('Insufficient balance')) {
                            document.getElementById('insufficientBalanceMessage').textContent = data.message;
                            insufficientBalanceModal.classList.remove('hidden');
                            insufficientBalanceModal.classList.add('flex');
                        } else {
                            alert(data.message);
                        }
                        btn.disabled = false;
                        btn.innerHTML = 'Submit Order';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML = 'Submit Order';
                }
            });
        }
    </script>
@endpush