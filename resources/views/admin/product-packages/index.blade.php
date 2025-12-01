@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Product Packages</h4>
                        <a href="{{ route('admin.product-packages.create') }}" class="btn btn-primary">Add Product
                            Package</a>
                    </div>
                    <div class="card-body">
                        <!-- Mobile: Accordion toggle button -->
                        <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2"
                            id="packageFilterAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header accordion-header-primary" id="packageFilterHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#packageFilterCollapse" aria-expanded="false"
                                        aria-controls="packageFilterCollapse">
                                        Filter
                                    </button>
                                </h2>
                                <div id="packageFilterCollapse" class="accordion-collapse collapse"
                                    aria-labelledby="packageFilterHeading" data-bs-parent="#packageFilterAccordion">
                                    <div class="accordion-body">
                                        <form method="GET" class="row g-2">
                                            <div class="col-12">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control form-control-sm" placeholder="Search by Package ID">
                                            </div>
                                            <div class="col-12">
                                                <select name="order_set_id"
                                                    class="default-select form-control form-control-sm wide">
                                                    <option value="">All Order Sets</option>
                                                    @foreach($orderSets as $orderSet)
                                                        <option value="{{ $orderSet->id }}" {{ (string) request('order_set_id') === (string) $orderSet->id ? 'selected' : '' }}>
                                                            {{ $orderSet->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <select name="type"
                                                    class="default-select form-control form-control-sm wide">
                                                    <option value="">All Types</option>
                                                    <option value="single" {{ request('type') === 'single' ? 'selected' : '' }}>Single</option>
                                                    <option value="combo" {{ request('type') === 'combo' ? 'selected' : '' }}>
                                                        Combo</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <select name="platform_id"
                                                    class="default-select form-control form-control-sm wide">
                                                    <option value="">All Platforms</option>
                                                    @foreach($platforms as $platform)
                                                        <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                                            {{ $platform->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <select name="status"
                                                    class="default-select form-control form-control-sm wide">
                                                    <option value="">All Status</option>
                                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-12 d-grid">
                                                <button class="btn btn-secondary" type="submit">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop/tablet: inline filter form -->
                        <form method="GET" class="row mb-3 g-2 d-none d-md-flex">
                            <div class="col-md-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm" placeholder="Search...">
                            </div>
                            <div class="col-md-2">
                                <select name="order_set_id" class="default-select form-control form-control-sm wide">
                                    <option value="">Order Sets</option>
                                    @foreach($orderSets as $orderSet)
                                        <option value="{{ $orderSet->id }}" {{ (string) request('order_set_id') === (string) $orderSet->id ? 'selected' : '' }}>
                                            {{ $orderSet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="default-select form-control form-control-sm wide">
                                    <option value="">Type</option>
                                    <option value="single" {{ request('type') === 'single' ? 'selected' : '' }}>Single
                                    </option>
                                    <option value="combo" {{ request('type') === 'combo' ? 'selected' : '' }}>Combo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="platform_id" class="default-select form-control form-control-sm wide">
                                    <option value="">Platform</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                            {{ $platform->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="default-select form-control form-control-sm wide">
                                    <option value="">Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                            </div>
                        </form>

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
                                        <th>Package ID</th>
                                        <th>Platform</th>
                                        <th>Products</th>
                                        <th>Package Amount</th>
                                        <th>Profit %</th>
                                        <th>Profit Amount</th>
                                        <th>Total with Profit</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productPackages as $productPackage)
                                        <tr>
                                            <td>{{ $loop->iteration + ($productPackages->currentPage() - 1) * $productPackages->perPage() }}
                                            </td>
                                            <td><span class="badge badge-secondary">{{ $productPackage->orderSet->name }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $productPackage->type === 'single' ? 'info' : 'primary' }}">
                                                    {{ ucfirst($productPackage->type) }}
                                                </span>
                                            </td>
                                            <td><strong>{{ $productPackage->package_id }}</strong></td>
                                            <td>{{ $productPackage->platform->name }}</td>
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
                                                        @foreach($productPackage->productPackageItems as $item)
                                                            <tr>
                                                                <td>{{ $item->product->name }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>${{ number_format($item->price, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td><strong>${{ number_format($productPackage->subtotal, 2) }}</strong></td>
                                            <td>{{ number_format($productPackage->profit_percentage, 2) }}%</td>
                                            <td>${{ number_format($productPackage->profit_amount, 2) }}</td>
                                            <td><strong>${{ number_format($productPackage->total_with_profit, 2) }}</strong>
                                            </td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.product-packages.toggle', $productPackage->id) }}"
                                                    class="toggle-form" data-id="{{ $productPackage->id }}">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" {{ $productPackage->is_active ? 'checked' : '' }}
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
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.product-packages.edit', $productPackage->id) }}">Edit</a>
                                                        <form method="POST"
                                                            action="{{ route('admin.product-packages.destroy', $productPackage->id) }}"
                                                            class="delete-form" data-id="{{ $productPackage->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item text-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">No product packages found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $productPackages->links() }}
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
@endsection