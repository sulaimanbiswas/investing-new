@extends('layouts.user.app')

@section('title', 'Records - ' . config('app.name'))

@section('content')
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="mb-4">
            <p class="text-sm text-white/80">Overview</p>
            <h2 class="text-2xl font-bold">My Transaction Records</h2>
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
            'all' => ['label' => 'All', 'icon' => 'fa-list', 'color' => 'gray'],
            'deposit' => ['label' => 'Deposits', 'icon' => 'fa-arrow-down', 'color' => 'blue'],
            'withdrawal' => ['label' => 'Withdrawals', 'icon' => 'fa-arrow-up', 'color' => 'rose'],
            'order_payment' => ['label' => 'Order Payments', 'icon' => 'fa-shopping-cart', 'color' => 'red'],
            'order_principal_return' => ['label' => 'Principal Returns', 'icon' => 'fa-undo', 'color' => 'indigo'],
            'order_profit' => ['label' => 'Order Profits', 'icon' => 'fa-coins', 'color' => 'emerald'],
        ];
    @endphp

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="flex overflow-x-auto gap-2 p-2" style="scrollbar-width: thin;">
            @foreach($tabs as $key => $tab)
                <a href="{{ route('records.index', ['tab' => $key]) }}"
                    class="flex-shrink-0 whitespace-nowrap px-4 py-2 rounded-xl text-xs font-semibold transition
                                                    {{ $activeTab === $key ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas {{ $tab['icon'] }} mr-1"></i>{{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">
                @if($activeTab === 'all')
                    All Transactions
                @elseif(isset($tabs[$activeTab]))
                    {{ $tabs[$activeTab]['label'] }}
                @else
                    Transactions
                @endif
            </h3>
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
@endsection