@extends('layouts.user.app')

@section('title', __('ui.orders') . ' - ' . config('app.name'))

@section('content')
    <div class="space-y-4">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center gap-3">
            @include('components.back-button')
            <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.orders') }}</h1>
        </div>

        <!-- Orders Table Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.sr') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.type') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.order_number_label') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.crypto') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.amount') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.profit') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.balance') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.status') }}</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">{{ __('ui.completed_at') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($orders as $index => $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $orders->firstItem() + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($order->type) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($order->manage_crypto && count($order->manage_crypto) > 0)
                                        <div class="space-y-1">
                                            @foreach($order->manage_crypto as $crypto)
                                                <div class="text-xs">
                                                    <span class="font-medium">{{ $crypto['name'] ?? __('ui.na') }}</span>
                                                    <span class="text-gray-500">{{ $crypto['quantity'] ?? 0 }} @
                                                        ${{ $crypto['price'] ?? 0 }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">{{ __('ui.no_products') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    ${{ number_format($order->order_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-medium">
                                    {{ number_format($order->profit_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($order->balance_after, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($order->status === 'paid')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('ui.paid') }}</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('ui.unpaid') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $order->paid_at ? $order->paid_at->format('Y-m-d h:i A') : __('ui.na') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                    {{ __('ui.no_orders_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection