@extends('admin.layouts.app')

@section('title', 'Admin | Orders')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Orders</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Orders</p>
                            <div class="d-flex flex-wrap">
                                <h3 class="font-w600 text-white mb-0 me-3">{{ number_format($totalOrders) }}</h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #198754 0%, #157347 100%);">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Amount</p>
                            <div class="d-flex flex-wrap">
                                <h3 class="font-w600 text-white mb-0 me-3">${{ number_format($totalAmount, 2) }}</h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Profit</p>
                            <div class="d-flex flex-wrap">
                                <h3 class="font-w600 text-white mb-0 me-3">${{ number_format($totalProfit, 2) }}</h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12l7 7 7-7"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Today Orders</p>
                            <div class="d-flex flex-wrap">
                                <h3 class="font-w600 text-white mb-0 me-3">{{ number_format($todayOrders) }}</h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Orders</h4>
                </div>
                <div class="card-body">
                    <!-- Desktop/tablet: inline filter form -->
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row mb-3 g-2 d-none d-md-flex">
                        <div class="col-md-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control form-control-sm" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="form-control form-control-sm" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="min_amount" value="{{ request('min_amount') }}"
                                class="form-control form-control-sm" placeholder="Min Amount">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="max_amount" value="{{ request('max_amount') }}"
                                class="form-control form-control-sm" placeholder="Max Amount">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th>SL #</th>
                                    <th>Order #</th>
                                    <th>User</th>
                                    <th>Platform</th>
                                    <th>Products</th>
                                    <th>Amount</th>
                                    <th>Commission</th>
                                    <th>Total Income</th>
                                    <th>Status</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                        <td><span class="badge badge-warning">#{{ $order->order_number }}</span></td>
                                        <td>
                                            <strong>{{ $order->userOrderSet->user->name }}</strong><br>
                                            <small class="text-muted">{{ "@" . $order->userOrderSet->user->username }}</small>
                                        </td>
                                        <td>{{ $order->userOrderSet->orderSet->platform->name ?? 'N/A' }}</td>
                                        <td>
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Qty</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($order->manage_crypto && count($order->manage_crypto) > 0)
                                                        @foreach($order->manage_crypto as $product)
                                                            <tr>
                                                                <td>{{ $product['name'] ?? 'N/A' }}</td>
                                                                <td>{{ $product['quantity'] ?? 0 }}</td>
                                                                <td>${{ number_format($product['price'] ?? 0, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">No products</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><strong>${{ number_format($order->order_amount, 2) }}</strong></td>
                                        <td><strong class="text-success">${{ number_format($order->profit_amount, 2) }}</strong>
                                        </td>
                                        <td><strong
                                                class="text-primary">${{ number_format($order->order_amount + $order->profit_amount, 2) }}</strong>
                                        </td>
                                        <td><span class="badge badge-success"><i
                                                    class="fas fa-check-circle me-1"></i>Completed</span></td>
                                        {{-- <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.orders.show', $order->id) }}">View</a>
                                                </div>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection