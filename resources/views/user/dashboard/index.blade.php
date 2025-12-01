@extends('layouts.user.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
    <!-- Quick Action Icons -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <a href="{{ route('deposit') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Recharge</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-wallet text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Withdrawal</span>
        </a>
        <a href="{{ route('teams') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Teams</span>
        </a>
        <a href="{{ route('invitation') }}"
            class="flex flex-col items-center gap-2 bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition">
            <div
                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <span class="text-xs font-medium text-gray-700">Invitation</span>
        </a>
    </div>

    <!-- Success Ticker Slider -->
    @include('components.success-slider')

    <!-- Platform Rules Section -->
    @include('components.platform-rules')

    <!-- Referral System Section -->
    <div id="referralSection">
        @include('components.referral-section')
    </div>
@endsection