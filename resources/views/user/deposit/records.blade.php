@extends('layouts.user.app')

@section('title', 'Deposit Records - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('profile.home') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Deposit Records</h1>
    </div>

    <!-- Deposit List -->
    <div class="space-y-4">
        @forelse($deposits as $deposit)
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg font-bold text-gray-800">Deposit</span>
                            @if($deposit->status === 'approved')
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">approved</span>
                            @elseif($deposit->status === 'pending')
                                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded">pending</span>
                            @elseif($deposit->status === 'rejected')
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">rejected</span>
                            @else
                                <span
                                    class="text-xs font-semibold text-gray-600 bg-gray-50 px-2 py-1 rounded">{{ $deposit->status }}</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">{{ $deposit->order_number }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 flex items-center gap-1 justify-end mb-1">
                            <i class="far fa-clock text-xs"></i>
                            <span>{{ $deposit->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="text-xs text-gray-400">{{ $deposit->created_at->format('H:i:s') }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        @if($deposit->gateway && $deposit->gateway->logo_path)
                            <img src="{{ asset($deposit->gateway->logo_path) }}" alt="{{ $deposit->gateway->name }}"
                                class="w-6 h-6 rounded-full object-cover">
                        @else
                            <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-wallet text-indigo-600 text-xs"></i>
                            </div>
                        @endif
                        <span class="text-sm text-gray-600">{{ $deposit->gateway->name ?? 'Gateway' }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-lg font-bold text-gray-800">
                        <i class="fas fa-coins text-yellow-500 text-base"></i>
                        <span>{{ number_format($deposit->amount, 2) }} {{ $deposit->currency }}</span>
                    </div>
                </div>

                @if($deposit->txn_id)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Transaction ID</div>
                        <div class="text-sm text-gray-700 font-mono break-all">{{ $deposit->txn_id }}</div>
                    </div>
                @endif

                @if($deposit->screenshot_path)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="text-xs text-gray-500 mb-2">Payment Screenshot</div>
                        <a href="{{ asset($deposit->screenshot_path) }}" target="_blank" class="inline-block">
                            <img src="{{ asset($deposit->screenshot_path) }}" alt="Screenshot"
                                class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-indigo-500 transition">
                        </a>
                    </div>
                @endif

                @if($deposit->admin_note)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="text-xs text-gray-500 mb-1">Admin Note</div>
                        <div class="text-sm text-gray-700">{{ $deposit->admin_note }}</div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">No Deposits Yet</h3>
                <p class="text-gray-500 mb-4">You haven't made any deposits yet</p>
                <a href="{{ route('deposit') }}"
                    class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Make Your First Deposit
                </a>
            </div>
        @endforelse
    </div>
@endsection