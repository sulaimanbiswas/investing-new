@extends('admin.layouts.app')

@section('title', 'Admin | Order Sets')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Order Sets</a></li>
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
            <h4 class="card-title">Order Sets</h4>
            <a href="{{ route('admin.order-sets.create') }}" class="btn btn-sm btn-primary">Create Order Set</a>
        </div>
        <div class="card-body">
            <!-- Mobile: Accordion toggle button -->
            <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2" id="orderSetFilterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header accordion-header-primary" id="orderSetFilterHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#orderSetFilterCollapse" aria-expanded="false"
                            aria-controls="orderSetFilterCollapse">
                            Filter
                        </button>
                    </h2>
                    <div id="orderSetFilterCollapse" class="accordion-collapse collapse"
                        aria-labelledby="orderSetFilterHeading" data-bs-parent="#orderSetFilterAccordion">
                        <div class="accordion-body">
                            <form method="GET" class="row g-2">
                                <div class="col-12">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control form-control-sm" placeholder="Search by name">
                                </div>
                                <div class="col-12">
                                    <select name="status" class="default-select form-control form-control-sm wide">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <select name="platform_id" class="default-select form-control form-control-sm wide">
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
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search by name">
                </div>
                <div class="col-md-3">
                    <select name="status" class="default-select form-control form-control-sm wide">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="platform_id" class="default-select form-control form-control-sm wide">
                        <option value="">All Platforms</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ (string) request('platform_id') === (string) $platform->id ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Platform</th>
                            <th>Total Orders</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderSets as $set)
                            <tr>
                                <td>{{ $set->id }}</td>
                                <td>{{ $set->name }}</td>
                                <td>{{ $set->platform?->name }}</td>
                                <td><span class="badge badge-primary">{{ $set->orders_count }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.order-sets.toggle', parameters: $set) }}"
                                        class="order-set-toggle-form">
                                        @csrf
                                        <button type="button"
                                            class="badge light {{ $set->is_active ? 'badge-success' : 'badge-danger' }} order-set-toggle-btn"
                                            data-name="{{ $set->name }}"
                                            data-current-status="{{ $set->is_active ? 'active' : 'inactive' }}">
                                            {{ $set->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn {{ $set->is_active ? 'btn-success' : 'btn-danger' }} light sharp"
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
                                            <a class="dropdown-item" href="{{ route('admin.order-sets.edit', $set) }}">Edit</a>
                                            <form method="POST"
                                                action="{{ route('admin.order-sets.toggle', parameters: $set) }}"
                                                class="order-set-toggle-form">
                                                @csrf
                                                <button type="button" class="dropdown-item order-set-toggle-btn"
                                                    data-name="{{ $set->name }}"
                                                    data-current-status="{{ $set->is_active ? 'active' : 'inactive' }}">
                                                    {{ $set->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.order-sets.destroy', parameters: $set) }}"
                                                class="order-set-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item order-set-delete-btn"
                                                    data-name="{{ $set->name }}">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No order sets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orderSets->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const hasSwal = () => typeof Swal !== 'undefined';
            const confirmToggle = (btn) => {
                const form = btn.closest('form');
                if (!form) return;
                const name = btn.dataset.name || 'this order set';
                const current = btn.dataset.currentStatus; // 'active' | 'inactive'
                const activating = current === 'inactive';
                if (!hasSwal()) {
                    if (window.confirm(activating ? `Activate ${name}?` : `Deactivate ${name}?`)) form.submit();
                    return;
                }
                Swal.fire({
                    title: activating ? 'Activate Order Set?' : 'Deactivate Order Set?',
                    text: activating ? `${name} will become Active.` : `${name} will become Inactive.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: activating ? '#198754' : '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: activating ? 'Yes, Activate' : 'Yes, Deactivate'
                }).then(r => { if (r.isConfirmed) form.submit(); });
            };
            const confirmDelete = (btn) => {
                const form = btn.closest('form');
                if (!form) return;
                const name = btn.dataset.name || 'this order set';
                if (!hasSwal()) {
                    if (window.confirm(`Delete ${name}? This cannot be undone.`)) form.submit();
                    return;
                }
                Swal.fire({
                    title: 'Delete Order Set?',
                    text: `Permanent deletion of ${name}. This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete'
                }).then(r => { if (r.isConfirmed) form.submit(); });
            };
            document.addEventListener('click', (e) => {
                const t = e.target.closest('.order-set-toggle-btn');
                if (t) { e.preventDefault(); confirmToggle(t); return; }
                const d = e.target.closest('.order-set-delete-btn');
                if (d) { e.preventDefault(); confirmDelete(d); return; }
            });
        })();
    </script>
@endpush