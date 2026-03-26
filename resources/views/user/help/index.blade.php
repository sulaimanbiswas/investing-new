@extends('layouts.user.app')

@section('title', __('ui.help') . ' - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.help') }}</h1>
    </div>

    <!-- Help Topics -->
    <div class="space-y-4">

        <!-- 1. About recharge -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-wallet text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('ui.help_about_recharge_title') }}</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50">
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        {{ __('ui.help_about_recharge_desc_1') }}
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ __('ui.help_about_recharge_desc_2') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. About -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('ui.help_about_withdrawal_title') }}</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-green-50 to-emerald-50">
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ __('ui.help_about_withdrawal_desc') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. About grabbing orders and freeing orders -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('ui.help_about_orders_title') }}</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-orange-50 to-red-50">
                    <p class="text-gray-700 text-sm leading-relaxed">
                        {{ __('ui.help_about_orders_desc') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 4. Platform Features -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('ui.help_platform_features_title') }}</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-purple-50 to-pink-50">
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>{{ __('ui.help_feature_levels') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>{{ __('ui.help_feature_daily_limits') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>{{ __('ui.help_feature_referral') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>{{ __('ui.help_feature_support') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 5. Commission Rates -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-percentage text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('ui.help_commission_rates_title') }}</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-indigo-50 to-blue-50">
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        {{ __('ui.help_commission_rates_desc') }}
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">{{ __('ui.level_1') }}</span>
                            <span class="text-yellow-600 font-bold">2.0% - 4.0%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">{{ __('ui.level_2') }}</span>
                            <span class="text-orange-600 font-bold">3.0% - 5.0%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">{{ __('ui.level_3') }}</span>
                            <span class="text-red-600 font-bold">4.0% - 6.0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Still Need Help Card -->
    <div class="mt-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-5 border-l-4 border-orange-500">
        <div class="flex items-start gap-3">
            <div class="bg-orange-500 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-question text-white text-sm"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-2">{{ __('ui.still_need_help') }}</h4>
                <p class="text-gray-700 text-sm leading-relaxed mb-3">
                    {{ __('ui.help_contact_support_desc') }}
                </p>
                <a href="{{ route('service.index') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:from-orange-600 hover:to-orange-700 transition">
                    <i class="fas fa-headset"></i>
                    <span>{{ __('ui.contact_support') }}</span>
                </a>
            </div>
        </div>
    </div>

@endsection