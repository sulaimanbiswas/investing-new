@extends('admin.layouts.app')

@section('title', 'Admin | Users')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
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
            <h4 class="card-title">All Users</h4>
        </div>
        <div class="card-body">
            <!-- Mobile: Accordion filter -->
            <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-3" id="userFilterAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header accordion-header-primary" id="userFilterHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#userFilterCollapse" aria-expanded="false" aria-controls="userFilterCollapse">
                            Filter
                        </button>
                    </h2>
                    <div id="userFilterCollapse" class="accordion-collapse collapse" aria-labelledby="userFilterHeading"
                        data-bs-parent="#userFilterAccordion">
                        <div class="accordion-body">
                            <form method="GET" class="row g-2">
                                <div class="col-12">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                        placeholder="Search by username or email">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">From Date</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">To Date</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
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
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search by username or email">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"
                        placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"
                        placeholder="To Date">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-secondary" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive recentOrderTable">
                <table class="table verticle-middle table-responsive-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Join Date</th>
                            <th>Current Balance</th>
                            <th>Task Status</th>
                            <th class="text-end" style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td><span class="text-primary">@</span>{{ $user->username }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('F j, Y') }}</td>
                                <td>{{ number_format($user->balance ?? 0, 2) }}</td>
                                <td>
                                    @if($user->userOrderSets && $user->userOrderSets->count() > 0)
                                        @foreach($user->userOrderSets as $userOrderSet)
                                            @php
                                                $percentage = $userOrderSet->completionPercentage();
                                                $statusClass = 'success';
                                                if ($percentage < 100) {
                                                    $statusClass = $percentage > 0 ? 'warning' : 'secondary';
                                                }
                                            @endphp
                                            <div class="mb-1">
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ $userOrderSet->orderSet->name }}: {{ $percentage }}%
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="badge badge-danger">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
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
                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                                <i class="fas fa-eye me-2"></i>View Details
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login as User
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                <i class="fas fa-plus-circle me-2"></i>Add Balance
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                <i class="fas fa-minus-circle me-2"></i>Deduct Balance
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                <i class="fas fa-history me-2"></i>Logins
                                            </a>
                                            <button type="button" class="dropdown-item user-ban-btn"
                                                data-name="{{ $user->username }}">
                                                <i class="fas fa-ban me-2"></i>Ban User
                                            </button>
                                            <button type="button" class="dropdown-item user-delete-btn text-danger"
                                                data-name="{{ $user->username }}">
                                                <i class="fas fa-trash me-2"></i>Delete User
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const hasSwal = () => typeof Swal !== 'undefined';

            const confirmBan = (btn) => {
                const name = btn.dataset.name || 'this user';
                if (!hasSwal()) {
                    if (window.confirm(`Ban ${name}?`)) {
                        // TODO: implement ban action
                        console.log('Ban user:', name);
                    }
                    return;
                }
                Swal.fire({
                    title: 'Ban User?',
                    text: `Are you sure you want to ban @${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Ban User'
                }).then(r => {
                    if (r.isConfirmed) {
                        // TODO: implement ban action
                        console.log('Ban user:', name);
                    }
                });
            };

            const confirmDelete = (btn) => {
                const name = btn.dataset.name || 'this user';
                if (!hasSwal()) {
                    if (window.confirm(`Delete ${name}? This cannot be undone.`)) {
                        // TODO: implement delete action
                        console.log('Delete user:', name);
                    }
                    return;
                }
                Swal.fire({
                    title: 'Delete User?',
                    text: `Permanent deletion of @${name}. This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Delete'
                }).then(r => {
                    if (r.isConfirmed) {
                        // TODO: implement delete action
                        console.log('Delete user:', name);
                    }
                });
            };

            document.addEventListener('click', (e) => {
                const banBtn = e.target.closest('.user-ban-btn');
                if (banBtn) {
                    e.preventDefault();
                    confirmBan(banBtn);
                    return;
                }
                const deleteBtn = e.target.closest('.user-delete-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    confirmDelete(deleteBtn);
                    return;
                }
            });
        })();
    </script>
@endpush