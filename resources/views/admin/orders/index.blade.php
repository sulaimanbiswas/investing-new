@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Orders</h4>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Add Order</a>
                </div>
                <div class="card-body">
                    <!-- Desktop Filters -->
                    <form method="GET" class="mb-4 d-none d-lg-block">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Search by Order ID"
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="order_set_id" class="default-select form-control wide">
                                    <option value="">All Order Sets</option>
                                    @foreach($orderSets as $orderSet)
                                        <option value="{{ $orderSet->id }}" {{ (string) request('order_set_id') === (string) $orderSet->id ? 'selected' : '' }}>
                                            {{ $orderSet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="default-select form-control wide">
                                    <option value="">All Types</option>
                                    <option value="single" {{ request('type') === 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="combo" {{ request('type') === 'combo' ? 'selected' : '' }}>Combo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="platform_id" class="default-select form-control wide">
                                    <option value="">All Platforms</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                            {{ $platform->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="default-select form-control wide">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary flex-fill">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Mobile Filters -->
                    <div class="d-lg-none mb-3">
                        <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobileFilters">
                            <i class="fas fa-filter me-2"></i>Filters
                        </button>
                        <div class="collapse mt-2" id="mobileFilters">
                            <form method="GET">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <input type="text" name="search" class="form-control" placeholder="Search by Order ID"
                                            value="{{ request('search') }}">
                                    </div>
                                    <div class="col-12">
                                        <select name="type" class="default-select form-control wide">
                                            <option value="">All Types</option>
                                            <option value="single" {{ request('type') === 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="combo" {{ request('type') === 'combo' ? 'selected' : '' }}>Combo</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="platform_id" class="default-select form-control wide">
                                            <option value="">All Platforms</option>
                                            @foreach($platforms as $platform)
                                                <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                                    {{ $platform->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="status" class="default-select form-control wide">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th>SL #</th>
                                    <th>Order Set</th>
                                    <th>Type</th>
                                    <th>Order ID</th>
                                    <th>Platform</th>
                                    <th>Products</th>
                                    <th>Order Amount</th>
                                    <th>Profit %</th>
                                    <th>Profit Amount</th>
                                    <th>Total with Profit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                        <td><span class="badge badge-secondary">{{ $order->orderSet->name }}</span></td>
                                        <td>
                                            <span class="badge badge-{{ $order->type === 'single' ? 'info' : 'primary' }}">
                                                {{ ucfirst($order->type) }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $order->order_id }}</strong></td>
                                        <td>{{ $order->platform->name }}</td>
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
                                                    @foreach($order->orderProducts as $item)
                                                        <tr>
                                                            <td>{{ $item->product->name }}</td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>${{ number_format($item->price, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><strong>${{ number_format($order->subtotal, 2) }}</strong></td>
                                        <td>{{ number_format($order->profit_percentage, 2) }}%</td>
                                        <td>${{ number_format($order->profit_amount, 2) }}</td>
                                        <td><strong>${{ number_format($order->total_with_profit, 2) }}</strong></td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.orders.toggle', $order->id) }}"
                                                class="toggle-form" data-id="{{ $order->id }}">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ $order->is_active ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                </div>
                                            </form>
                                        </td>
                                        <td>
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
                                                    <a class="dropdown-item" href="{{ route('admin.orders.edit', $order->id) }}">Edit</a>
                                                    <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                                        class="delete-form" data-id="{{ $order->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No orders found</td>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.toggle-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const checkbox = this.querySelector('input[type="checkbox"]');
            const isActive = checkbox.checked;

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${isActive ? 'activate' : 'deactivate'} this order?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                } else {
                    checkbox.checked = !isActive;
                }
            });
        });
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
