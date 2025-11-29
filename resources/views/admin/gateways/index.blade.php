@extends('admin.layouts.app')

@section('title', 'Admin | Gateways')

@section('content')

    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">Gateways</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="javascript:void(0)">Manage Gateways</a>
            </li>
        </ol>
    </div>

    @if(session('status'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" class="me-2">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <strong>Success!</strong> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
            </button>
        </div>
    @endif

    {{-- @(session('error'))
    <div class="alert alert-danger solid alert-dismissible fade show">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
            stroke-linecap="round" stroke-linejoin="round" class="me-2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
    </div>
    @endif --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gateways</h4>
                    <a href="{{ route('admin.gateways.create') }}"> <button type="button"
                            class="btn btn-primary btn-sm">Create</button></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr>
                                    <th style="width: 80px">#</th>
                                    <th>Gateway</th>
                                    <th>Type</th>
                                    <th>Country</th>
                                    <th>STATUS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gateways as $gateway)
                                    <tr>
                                        <td><strong class="text-black">{{ $gateway->id }}</strong></td>
                                        <td>{{ $gateway->name }}</td>
                                        <td>{{ $gateway->type }}</td>
                                        <td>{{ $gateway->country }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.gateways.toggle', parameters: $gateway) }}"
                                                class="gateway-toggle-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    class="badge light {{ $gateway->is_active ? 'badge-success' : 'badge-danger' }} gateway-toggle-btn"
                                                    data-name="{{ $gateway->name }}"
                                                    data-current-status="{{ $gateway->is_active ? 'active' : 'inactive' }}">
                                                    {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button"
                                                    class="btn {{ $gateway->is_active ? 'btn-success' : 'btn-danger' }} light sharp"
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
                                                        href="{{ route('admin.gateways.edit', $gateway) }}">Edit</a>
                                                    <form method="POST"
                                                        action="{{ route('admin.gateways.toggle', parameters: $gateway) }}"
                                                        class="gateway-toggle-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button" class="dropdown-item gateway-toggle-btn"
                                                            data-name="{{ $gateway->name }}"
                                                            data-current-status="{{ $gateway->is_active ? 'active' : 'inactive' }}">
                                                            {{ $gateway->is_active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('admin.gateways.destroy', parameters: $gateway) }}"
                                                        class="gateway-delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item gateway-delete-btn"
                                                            data-name="{{ $gateway->name }}">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No gateways found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $gateways->links() }}
                </div>
            </div>
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
                const name = btn.dataset.name || 'this gateway';
                const current = btn.dataset.currentStatus; // 'active' | 'inactive'
                const activating = current === 'inactive';
                if (!hasSwal()) {
                    const ok = window.confirm(activating ? `Activate ${name}?` : `Deactivate ${name}?`);
                    if (ok) form.submit();
                    return;
                }
                Swal.fire({
                    title: activating ? 'Activate Gateway?' : 'Deactivate Gateway?',
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
                const name = btn.dataset.name || 'this gateway';
                if (!hasSwal()) {
                    const ok = window.confirm(`Delete ${name}? This cannot be undone.`);
                    if (ok) form.submit();
                    return;
                }
                Swal.fire({
                    title: 'Delete Gateway?',
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
                const toggleBtn = e.target.closest('.gateway-toggle-btn');
                if (toggleBtn) {
                    e.preventDefault();
                    confirmToggle(toggleBtn);
                    return;
                }
                const deleteBtn = e.target.closest('.gateway-delete-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    confirmDelete(deleteBtn);
                    return;
                }
            });

            // Diagnostics
            console.info('[Gateways] SweetAlert integration loaded. Swal present:', hasSwal());
        })();
    </script>
@endpush