@extends('admin.layouts.app')

@section('title', 'Admin | Manage Order Set')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.order-sets.index') }}">Order Sets</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Manage</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <!-- Order Set Details Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Order Set: {{ $orderSet->name }}</h4>
                    <div>
                        <a href="{{ route('admin.order-sets.edit', $orderSet) }}" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit Order Set
                        </a>
                        <a href="{{ route('admin.order-sets.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Platform:</strong></p>
                            <p>{{ $orderSet->platform->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Total Packages:</strong></p>
                            <p><span class="badge badge-primary">{{ $orderSet->productPackages->count() }}</span></p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Status:</strong></p>
                            <p>
                                <span class="badge light {{ $orderSet->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $orderSet->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-2"><strong>Created:</strong></p>
                            <p>{{ $orderSet->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Packages Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Product Packages</h4>
                    <a href="{{ route('admin.product-packages.create', ['order_set_id' => $orderSet->id]) }}"
                        class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Create Package
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive recentOrderTable">
                        <table class="table verticle-middle table-responsive-md">
                            <thead>
                                <tr>
                                    <th>SL #</th>
                                    <th>Package ID</th>
                                    <th>Type</th>
                                    <th>Platform</th>
                                    <th>Products</th>
                                    <th>Package Amount</th>
                                    <th>Profit %</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orderSet->productPackages as $index => $package)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $package->package_id }}</strong></td>
                                        <td>
                                            <span class="badge badge-{{ $package->type === 'single' ? 'info' : 'primary' }}">
                                                {{ ucfirst($package->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $package->platform->name }}</td>
                                        <td>
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="thead-primary table-sm">
                                                    <tr class="">
                                                        <th>Product</th>
                                                        <th>Qty</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($package->productPackageItems as $item)
                                                        <tr>
                                                            <td>{{ $item->product->name }}</td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>${{ number_format($item->price, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td><strong>${{ number_format($package->subtotal, 2) }}</strong></td>
                                        <td>{{ number_format($package->profit_percentage, 2) }}%</td>
                                        <td><strong>${{ number_format($package->total_with_profit, 2) }}</strong></td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.product-packages.toggle', $package->id) }}"
                                                class="toggle-form" data-id="{{ $package->id }}">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" {{ $package->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp btn-sm"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
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
                                                        href="{{ route('admin.product-packages.edit', ['product_package' => $package->id, 'order_set_id' => $orderSet->id]) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('admin.product-packages.destroy', $package->id) }}"
                                                        class="delete-form" data-id="{{ $package->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-box-open fa-3x text-muted"></i>
                                            </div>
                                            <p class="text-muted mb-3">No product packages found for this order set.</p>
                                            <a href="{{ route('admin.product-packages.create', ['order_set_id' => $orderSet->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Create First Package
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.toggle-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const checkbox = this.querySelector('input[type="checkbox"]');
                const isActive = checkbox.checked;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to ${isActive ? 'activate' : 'deactivate'} this product package?`,
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
            form.addEventListener('submit', function (e) {
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