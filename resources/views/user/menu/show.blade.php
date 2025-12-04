@extends('layouts.user.app')

@section('content')

    <!-- Platform Name Header -->
    <div class="mb-6 text-center">
        <div class="mb-4">
            @if($platform->image)
                <img src="{{ asset($platform->image) }}" alt="{{ $platform->name }}"
                    class="w-24 h-24 rounded-2xl object-cover shadow-xl mx-auto border-4 border-white">
            @else
                <div
                    class="w-24 h-24 bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl mx-auto border-4 border-white">
                    <i class="fas fa-store text-white text-4xl"></i>
                </div>
            @endif
        </div>
        <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-1">
            {{ $platform->name }}</h2>
        <p class="text-gray-500 text-sm">Commission Rate: <span
                class="font-bold text-green-600">{{ number_format($platform->commission, 1) }}%</span></p>
    </div>

    <!-- Account Balance Card -->
    <div class="mb-6 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-wallet text-white text-xl"></i>
                    <p class="text-white text-sm opacity-90">Account Balance</p>
                </div>
                <p class="text-white text-4xl font-bold mb-1">{{ number_format($user->balance, 2) }}</p>
                <p class="text-white text-lg opacity-90">USDT</p>
            </div>
        </div>
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

    <!-- Grab Order Button -->
    <div class="mb-6">
        <button type="button" id="grabOrderBtn"
            class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold py-5 rounded-2xl shadow-xl hover:from-gray-600 hover:to-gray-700 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3">
            <i class="fas fa-hand-holding-usd text-2xl"></i>
            <span class="text-lg">Grab the order immediately</span>
        </button>
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
                <span><span class="font-bold text-green-600">{{ number_format($platform->commission, 1) }}%</span> of the
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

@push('scripts')
    <script>
        document.getElementById('grabOrderBtn').addEventListener('click', function () {
            // Placeholder for grab order functionality
            alert('Order functionality will be implemented soon!');
        });
    </script>
@endpush