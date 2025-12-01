@extends('admin.layouts.app')

@section('title', 'Admin | Platform Rules')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Platform Rules</a></li>
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
            <h4 class="card-title">Platform Rules</h4>
            <a href="{{ route('admin.platform-rule.create') }}" class="btn btn-sm btn-primary">Create Rule</a>
        </div>
        <div class="card-body">
            <!-- Mobile: Accordion toggle button -->
            <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2" id="ruleFilterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header accordion-header-primary" id="ruleFilterHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#ruleFilterCollapse" aria-expanded="false" aria-controls="ruleFilterCollapse">
                            Filter
                        </button>
                    </h2>
                    <div id="ruleFilterCollapse" class="accordion-collapse collapse" aria-labelledby="ruleFilterHeading"
                        data-bs-parent="#ruleFilterAccordion">
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
                            <th>Sort By</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $rule)
                            <tr>
                                <td>{{ $rule->id }}</td>
                                <td>@if($rule->image)
                                    <img src="{{ $rule->image }}" alt="image" style="height:40px;">
                                @endif {{ $rule->name }}
                                </td>
                                <td>{{ $rule->sort_by }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.platform-rule.toggle', parameters: $rule) }}"
                                        class="rule-toggle-form">
                                        @csrf
                                        <button type="button"
                                            class="badge light {{ $rule->is_active ? 'badge-success' : 'badge-danger' }} rule-toggle-btn"
                                            data-name="{{ $rule->name }}"
                                            data-current-status="{{ $rule->is_active ? 'active' : 'inactive' }}">
                                            {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn {{ $rule->is_active ? 'btn-success' : 'btn-danger' }} light sharp"
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
                                                href="{{ route('admin.platform-rule.edit', $rule) }}">Edit</a>
                                            <form method="POST"
                                                action="{{ route('admin.platform-rule.toggle', parameters: $rule) }}"
                                                class="rule-toggle-form">
                                                @csrf
                                                <button type="button" class="dropdown-item rule-toggle-btn"
                                                    data-name="{{ $rule->name }}"
                                                    data-current-status="{{ $rule->is_active ? 'active' : 'inactive' }}">
                                                    {{ $rule->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.platform-rule.destroy', parameters: $rule) }}"
                                                class="rule-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item rule-delete-btn"
                                                    data-name="{{ $rule->name }}">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No rules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $rules->links() }}
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
                const name = btn.dataset.name || 'this rule';
                const current = btn.dataset.currentStatus; // 'active' | 'inactive'
                const activating = current === 'inactive';
                if (!hasSwal()) {
                    if (window.confirm(activating ? `Activate ${name}?` : `Deactivate ${name}?`)) form.submit();
                    return;
                }
                Swal.fire({
                    title: activating ? 'Activate Rule?' : 'Deactivate Rule?',
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
                const name = btn.dataset.name || 'this rule';
                if (!hasSwal()) {
                    if (window.confirm(`Delete ${name}? This cannot be undone.`)) form.submit();
                    return;
                }
                Swal.fire({
                    title: 'Delete Rule?',
                    text: `Permanent deletion of ${name}. This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete'
                }).then(r => { if (r.isConfirmed) form.submit(); });
            };
            document.addEventListener('click', (e) => {
                const t = e.target.closest('.rule-toggle-btn');
                if (t) { e.preventDefault(); confirmToggle(t); return; }
                const d = e.target.closest('.rule-delete-btn');
                if (d) { e.preventDefault(); confirmDelete(d); return; }
            });
        })();
    </script>
@endpush