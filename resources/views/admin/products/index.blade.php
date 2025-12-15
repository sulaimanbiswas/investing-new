@extends('admin.layouts.app')

@section('title', 'Admin | Products')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Products</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Products</h4>
            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">Create Product</a>
        </div>
        <div class="card-body">
            <!-- Mobile: Accordion toggle button -->
            <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-3" id="productFilterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header accordion-header-primary" id="productFilterHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#productFilterCollapse" aria-expanded="false"
                            aria-controls="productFilterCollapse">
                            Filter
                        </button>
                    </h2>
                    <div id="productFilterCollapse" class="accordion-collapse collapse"
                        aria-labelledby="productFilterHeading" data-bs-parent="#productFilterAccordion">
                        <div class="accordion-body">
                            <form method="GET" class="row g-2">
                                <div class="col-12">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control form-control-sm" placeholder="Search by name or price">
                                </div>

                                <div class="col-6">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        class="form-control form-control-sm" placeholder="Min price" step="0.01">
                                </div>

                                <div class="col-6">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        class="form-control form-control-sm" placeholder="Max price" step="0.01">
                                </div>

                                <div class="col-12">
                                    <select name="status" class="default-select form-control form-control-sm wide" required>
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <select name="platform_id" class="default-select form-control form-control-sm wide"
                                        required>
                                        <option value="">All Platforms</option>
                                        @foreach($platforms as $platform)
                                            <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                                {{ $platform->name }}
                                            </option>
                                        @endforeach
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
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search by name or price">
                </div>

                <div class="col-md-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                        class="form-control form-control-sm" placeholder="Min price" step="0.01">
                </div>

                <div class="col-md-2">
                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                        class="form-control form-control-sm" placeholder="Max price" step="0.01">
                </div>

                <div class="col-md-2">
                    <select name="status" class="default-select form-control form-control-sm wide" required>
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="platform_id" class="default-select form-control form-control-sm wide" required>
                        <option value="">All Platforms</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive recentOrderTable">
                <table class="table verticle-middle table-responsive-md">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Platform</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 80px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td> @if($product->image)
                                    <img src="{{ $product->image }}" alt="image" style="height:40px;">
                                @endif {{ $product->name }}
                                </td>
                                <td>${{ rtrim(rtrim(number_format($product->price, 2, '.', ''), '0'), '.') }}</td>
                                <td>{{ $product->platform?->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.products.toggle', parameters: $product) }}"
                                        class="product-toggle-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button"
                                            class="badge light {{ $product->is_active ? 'badge-success' : 'badge-danger' }} product-toggle-btn"
                                            data-name="{{ $product->name }}"
                                            data-current-status="{{ $product->is_active ? 'active' : 'inactive' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn {{ $product->is_active ? 'btn-success' : 'btn-danger' }} light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                            <form method="POST"
                                                action="{{ route('admin.products.toggle', parameters: $product) }}"
                                                class="product-toggle-form">
                                                @csrf
                                                <button type="button" class="dropdown-item product-toggle-btn"
                                                    data-name="{{ $product->name }}"
                                                    data-current-status="{{ $product->is_active ? 'active' : 'inactive' }}">
                                                    {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.products.destroy', parameters: $product) }}"
                                                class="product-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item product-delete-btn"
                                                    data-name="{{ $product->name }}">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 CDN (if not already included globally) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const hasSwal = () => typeof Swal !== 'undefined';

            const confirmToggle = (btn) => {
                const form = btn.closest('form');
                if (!form) return console.warn('Toggle form not found for button:', btn);
                const name = btn.dataset.name || 'this product';
                const current = btn.dataset.currentStatus; // 'active' | 'inactive'
                const activating = current === 'inactive';

                if (!hasSwal()) {
                    const ok = window.confirm(activating ? `Activate ${name}?` : `Deactivate ${name}?`);
                    if (ok) form.submit();
                    return;
                }

                Swal.fire({
                    title: activating ? 'Activate Product?' : 'Deactivate Product?',
                    text: activating ? `${name} will become Active.` : `${name} will become Inactive.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: activating ? '#198754' : '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: activating ? 'Yes, Activate' : 'Yes, Deactivate'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            };

            const confirmDelete = (btn) => {
                const form = btn.closest('form');
                if (!form) return console.warn('Delete form not found for button:', btn);
                const name = btn.dataset.name || 'this product';

                if (!hasSwal()) {
                    const ok = window.confirm(`Delete ${name}? This cannot be undone.`);
                    if (ok) form.submit();
                    return;
                }

                Swal.fire({
                    title: 'Delete Product?',
                    text: `Permanent deletion of ${name}. This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            };

            // Delegated handler to support future dynamic rows
            document.addEventListener('click', (e) => {
                const toggleBtn = e.target.closest('.product-toggle-btn');
                if (toggleBtn) {
                    e.preventDefault();
                    confirmToggle(toggleBtn);
                    return;
                }
                const deleteBtn = e.target.closest('.product-delete-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    confirmDelete(deleteBtn);
                    return;
                }
            });

            console.info('[Products] SweetAlert integration loaded. Swal present:', hasSwal());
        })();
    </script>
@endpush