@extends('layouts.user.app')

@section('title', 'Orders - ' . config('app.name'))

@section('content')
    <div class="space-y-4">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left text-gray-700"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
        </div>

        <!-- Orders Table Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Sr</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Order #</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Crypto</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Profit</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Balance</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Completed At</th>
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
                                                    <span class="font-medium">{{ $crypto['name'] ?? 'N/A' }}</span>
                                                    <span class="text-gray-500">{{ $crypto['quantity'] ?? 0 }} @ ${{ $crypto['price'] ?? 0 }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">No products</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($order->order_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-medium">{{ number_format($order->profit_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($order->balance_after, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($order->status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $order->paid_at ? $order->paid_at->format('Y-m-d h:i A') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                    No orders found
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