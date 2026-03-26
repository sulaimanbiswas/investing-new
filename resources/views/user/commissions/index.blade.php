@extends('layouts.user.app')

@section('title', __('ui.referral_commissions') . ' - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('profile.home') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.referral_commissions') }}</h1>
    </div>

    <!-- Total Earnings Card -->
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-indigo-100 text-sm mb-1">{{ __('ui.total_earnings') }}</p>
                <h2 class="text-3xl font-bold">{{ number_format($totalEarnings, 2) }} USDT</h2>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <i class="fas fa-coins text-3xl"></i>
            </div>
        </div>
        
        <!-- Level Breakdown -->
        <div class="grid grid-cols-3 gap-3 mt-4 pt-4 border-t border-white/20">
            <div class="text-center">
                <p class="text-indigo-100 text-xs mb-1">{{ __('ui.level_1') }}</p>
                <p class="font-semibold text-sm">{{ number_format($totals['level1'], 2) }}</p>
            </div>
            <div class="text-center">
                <p class="text-indigo-100 text-xs mb-1">{{ __('ui.level_2') }}</p>
                <p class="font-semibold text-sm">{{ number_format($totals['level2'], 2) }}</p>
            </div>
            <div class="text-center">
                <p class="text-indigo-100 text-xs mb-1">{{ __('ui.level_3') }}</p>
                <p class="font-semibold text-sm">{{ number_format($totals['level3'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Commission History -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('ui.commission_history') }}</h3>
        
        <div class="space-y-3">
            @forelse($commissions as $commission)
                <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-800">{{ $commission->referredUser->username ?? $commission->referredUser->email }}</span>
                                @if($commission->level == 1)
                                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ __('ui.level_1') }}</span>
                                @elseif($commission->level == 2)
                                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">{{ __('ui.level_2') }}</span>
                                @else
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded">{{ __('ui.level_3') }}</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="far fa-clock"></i>
                                {{ $commission->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">
                                +{{ number_format($commission->commission_amount, 2) }} USDT
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $commission->commission_percentage }}%
                            </div>
                        </div>
                    </div>

                    <!-- Deposit Info -->
                    <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fas fa-hand-holding-usd text-indigo-500"></i>
                                {{ __('ui.deposit_amount') }}
                            </span>
                            <span class="font-semibold text-gray-800">
                                {{ number_format($commission->deposit->approved_amount ?? $commission->deposit->amount, 2) }} USDT
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                <i class="fas fa-wallet text-purple-500"></i>
                                {{ __('ui.balance_after') }}
                            </span>
                            <span class="font-semibold text-gray-800">
                                {{ number_format($commission->balance_after, 2) }} USDT
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-gray-800 font-semibold mb-1">{{ __('ui.no_commissions_yet') }}</h4>
                    <p class="text-gray-500 text-sm">{{ __('ui.no_referral_commissions_yet') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($commissions->hasPages())
        <div class="bg-white rounded-xl shadow-sm p-4">
            {{ $commissions->links() }}
        </div>
    @endif

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-6">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-blue-800 mb-2">{{ __('ui.how_it_works') }}</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li><strong>{{ __('ui.level_1') }}:</strong> {{ __('ui.commission_level_1_desc') }}</li>
                    <li><strong>{{ __('ui.level_2') }}:</strong> {{ __('ui.commission_level_2_desc') }}</li>
                    <li><strong>{{ __('ui.level_3') }}:</strong> {{ __('ui.commission_level_3_desc') }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
