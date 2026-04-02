@extends('layouts.user.app')

@section('title', __('ui.invitation_program') . ' - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.invitation_program') }}</h1>
    </div>

    <!-- Referral System Section -->
    @include('components.referral-section')

    <!-- How It Works Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mt-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-indigo-600"></i>
            {{ __('ui.how_it_works') }}
        </h3>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">1</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">{{ __('ui.share_your_code') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('ui.share_your_code_desc') }}</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">2</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">{{ __('ui.friends_sign_up') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('ui.friends_sign_up_desc') }}</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">3</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">{{ __('ui.earn_rewards') }}</h4>
                    <p class="text-gray-600 text-sm">{{ __('ui.earn_rewards_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div
        class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-sm p-4 sm:p-6 mt-4 border border-indigo-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-gift text-purple-600"></i>
            {{ __('ui.referral_benefits') }}
        </h3>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('ui.commission_earnings') }}</h4>
                </div>
                <p class="text-gray-600 text-sm">{{ __('ui.commission_earnings_desc') }}</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('ui.team_building') }}</h4>
                </div>
                <p class="text-gray-600 text-sm">{{ __('ui.team_building_desc') }}</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('ui.unlimited_referrals') }}</h4>
                </div>
                <p class="text-gray-600 text-sm">{{ __('ui.unlimited_referrals_desc') }}</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">{{ __('ui.passive_income') }}</h4>
                </div>
                <p class="text-gray-600 text-sm">{{ __('ui.passive_income_desc') }}</p>
            </div>
        </div>
    </div>
@endsection