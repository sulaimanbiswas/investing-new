@extends('layouts.user.app')

@section('title', __('ui.withdrawal_records') . ' - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.withdrawal_records') }}</h1>
    </div>

    <div class="bg-gradient-to-br from-green-600 to-teal-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-history text-white text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-white/80">{{ __('ui.view_your_history') }}</div>
                <h2 class="text-2xl font-bold">{{ __('ui.withdrawal_records') }}</h2>
            </div>
        </div>
    </div>

    @if($withdrawals->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('ui.no_withdrawal_records') }}</h3>
            <p class="text-gray-500 mb-4">{{ __('ui.no_withdrawal_requests') }}</p>
            <a href="{{ route('withdrawal') }}"
                class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                {{ __('ui.make_withdrawal') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($withdrawals as $withdrawal)
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ __('ui.withdraw_no') }} <span
                                    class="font-semibold text-gray-900">{{ $withdrawal->order_number }}</span></p>
                            <p class="text-xs text-gray-500">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($withdrawal->status === 'pending')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                <i class="fas fa-clock mr-1"></i>{{ __('ui.pending') }}
                            </span>
                        @elseif($withdrawal->status === 'approved')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                <i class="fas fa-check-circle mr-1"></i>{{ __('ui.approved') }}
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                <i class="fas fa-times-circle mr-1"></i>{{ __('ui.rejected') }}
                            </span>
                        @endif
                    </div>

                    <div class="border-t border-gray-100 pt-3 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('ui.amount') }}:</span>
                            <span class="font-bold text-gray-900">{{ number_format($withdrawal->amount, 2) }}
                                {{ $withdrawal->currency }}</span>
                        </div>
                        {{-- <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Wallet Address:</span>
                            <span class="font-mono text-xs text-gray-900 break-all">{{ Str::limit($withdrawal->wallet_address, 20,
                                '...') }}</span>
                        </div> --}}
                        @if($withdrawal->admin_note)
                            <div class="bg-gray-50 rounded-lg p-3 mt-2">
                                <p class="text-xs text-gray-600 mb-1">{{ __('ui.admin_note') }}:</p>
                                <p class="text-sm text-gray-800">{{ $withdrawal->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $withdrawals->links() }}
        </div>
    @endif
@endsection