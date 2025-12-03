@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Order List</h3>

        <div class="table-responsive">
            <table class="table">
                <thead style="background-color: #ff6837; color: white;">
                    <tr>
                        <th>Sr</th>
                        <th>Type</th>
                        <th>Order #</th>
                        <th>Manage Crypto</th>
                        <th>Order Amount</th>
                        <th>Profit</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Completed At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>{{ ucfirst($order->type) }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>
                                @if($order->manage_crypto && count($order->manage_crypto) > 0)
                                    <table class="table table-sm mb-0" style="background-color: #ff6837; color: white;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->manage_crypto as $crypto)
                                                <tr>
                                                    <td>{{ $crypto['name'] ?? 'N/A' }}</td>
                                                    <td>{{ $crypto['quantity'] ?? 0 }}</td>
                                                    <td>{{ $crypto['price'] ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted mb-0">No products</p>
                                @endif
                            </td>
                            <td>${{ number_format($order->order_amount, 2) }}</td>
                            <td>{{ number_format($order->profit_amount, 2) }}</td>
                            <td>{{ number_format($order->balance_after, 2) }}</td>
                            <td>
                                @if($order->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>{{ $order->paid_at ? $order->paid_at->format('Y-m-d h:i A') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection