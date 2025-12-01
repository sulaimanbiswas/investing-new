@extends('admin.layouts.app')

@section('title', 'Admin | User Detail - ' . $user->username)

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $user->username }}</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger solid alert-dismissible fade show">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Wallet Balance -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Wallet Balance</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['balance'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M28.3333 5.66667H25.5V2.83333C25.5 2.08696 25.2034 1.37107 24.6752 0.842783C24.1471 0.314493 23.4312 0.0178833 22.6849 0.0178833H11.3151C10.5688 0.0178833 9.85286 0.314493 9.32457 0.842783C8.79628 1.37107 8.49967 2.08696 8.49967 2.83333V5.66667H5.66634C4.17384 5.66667 2.83301 6.66 2.83301 8.5V11.3333H31.1663V8.5C31.1663 6.66 29.8255 5.66667 28.3333 5.66667ZM22.6663 5.66667H11.333V2.83333H22.6663V5.66667Z"
                                    fill="white" />
                                <path
                                    d="M2.83301 28.3333C2.83301 29.0797 3.12962 29.7956 3.65791 30.3239C4.1862 30.8522 4.90209 31.1488 5.64846 31.1488H28.3485C29.0948 31.1488 29.8107 30.8522 30.339 30.3239C30.8673 29.7956 31.1639 29.0797 31.1639 28.3333V14.1667H2.83301V28.3333ZM19.8333 19.8333H22.6667V22.6667H19.8333V19.8333ZM19.8333 25.5H22.6667V28.3333H19.8333V25.5ZM14.1667 19.8333H17V22.6667H14.1667V19.8333ZM14.1667 25.5H17V28.3333H14.1667V25.5ZM8.5 19.8333H11.3333V22.6667H8.5V19.8333ZM8.5 25.5H11.3333V28.3333H8.5V25.5Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposits -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-warning">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Deposits</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['total_deposits'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 2.83333C9.18 2.83333 2.83333 9.18 2.83333 17C2.83333 24.82 9.18 31.1667 17 31.1667C24.82 31.1667 31.1667 24.82 31.1667 17C31.1667 9.18 24.82 2.83333 17 2.83333ZM22.6667 18.4167H18.4167V22.6667H15.5833V18.4167H11.3333V15.5833H15.5833V11.3333H18.4167V15.5833H22.6667V18.4167Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdrawals -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-info">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Withdrawals</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['total_withdrawals'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 2.83333C9.18 2.83333 2.83333 9.18 2.83333 17C2.83333 24.82 9.18 31.1667 17 31.1667C24.82 31.1667 31.1667 24.82 31.1667 17C31.1667 9.18 24.82 2.83333 17 2.83333ZM22.6667 18.4167H11.3333V15.5833H22.6667V18.4167Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-success">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Transactions</p>
                            <div class="d-flex flex-wrap">
                                <h2 class="fs-40 font-w600 text-white mb-0 me-3">
                                    {{ $stats['total_transactions'] }}
                                </h2>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24.0833 11.3333L21.25 8.5L18.4167 11.3333H22.6667V18.4167H25.5V11.3333H24.0833ZM12.75 22.6667H8.5V15.5833H5.66667V22.6667H2.83333L5.66667 25.5L8.5 28.3333L11.3333 25.5L12.75 24.0833V22.6667ZM2.83333 8.5V11.3333H19.8333V8.5H2.83333ZM14.1667 22.6667V25.5H31.1667V22.6667H14.1667Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invest -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-danger">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Invest</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['total_invest'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.83333 28.3333H31.1667V31.1667H2.83333V28.3333ZM5.66667 25.5H8.5V19.8333H5.66667V25.5ZM11.3333 25.5H14.1667V14.1667H11.3333V25.5ZM17 25.5H19.8333V8.5H17V25.5ZM22.6667 25.5H25.5V2.83333H22.6667V25.5Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Referral Commission -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-secondary">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Referral Commission</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['total_referral_commission'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.3333 17C11.3333 18.5471 11.9479 20.0308 13.0419 21.1248C14.1359 22.2188 15.6196 22.8333 17.1667 22.8333C18.7138 22.8333 20.1975 22.2188 21.2915 21.1248C22.3855 20.0308 23 18.5471 23 17C23 15.4529 22.3855 13.9692 21.2915 12.8752C20.1975 11.7812 18.7138 11.1667 17.1667 11.1667C15.6196 11.1667 14.1359 11.7812 13.0419 12.8752C11.9479 13.9692 11.3333 15.4529 11.3333 17ZM28.3333 9.91667C26.5 9.91667 25 8.41667 25 6.58333C25 4.75 26.5 3.25 28.3333 3.25C30.1667 3.25 31.6667 4.75 31.6667 6.58333C31.6667 8.41667 30.1667 9.91667 28.3333 9.91667ZM31.1667 11.3333C32.45 11.3333 33.5 10.2833 33.5 9C33.5 7.71667 32.45 6.66667 31.1667 6.66667V11.3333ZM31.1667 14.1667V25.5C31.1667 27.15 29.8167 28.5 28.1667 28.5H6C4.35 28.5 3 27.15 3 25.5V14.1667H5.83333V25.5H28.3333V14.1667H31.1667Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Binary Commission -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-primary">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total Binary Commission</p>
                            <div class="d-flex flex-wrap">
                                <h3 class=" font-w600 text-white mb-0 me-3">
                                    USDT {{ number_format($stats['total_binary_commission'], 2) }}
                                </h3>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M28.3333 22.6667H25.5V17H19.8333V22.6667H14.1667V17H8.5V22.6667H5.66667C4.18333 22.6667 2.83333 24.0167 2.83333 25.5V28.3333H31.1667V25.5C31.1667 24.0167 29.8167 22.6667 28.3333 22.6667ZM19.8333 8.5H14.1667V14.1667H8.5V11.3333C8.5 9.85 7.15 8.5 5.66667 8.5H2.83333V11.3333H5.66667V14.1667C5.66667 15.65 7.01667 17 8.5 17H14.1667V19.8333H19.8333V17H25.5C26.9833 17 28.3333 15.65 28.3333 14.1667V11.3333H31.1667V8.5H28.3333C26.85 8.5 25.5 9.85 25.5 11.3333V14.1667H19.8333V8.5ZM17 2.83333C15.5167 2.83333 14.1667 4.18333 14.1667 5.66667H19.8333C19.8333 4.18333 18.4833 2.83333 17 2.83333Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total BV -->
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card gradient-bx text-white bg-dark">
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="media-body">
                            <p class="mb-1">Total BV</p>
                            <div class="d-flex flex-wrap">
                                <h2 class="fs-40 font-w600 text-white mb-0 me-3">
                                    {{ $stats['total_bv'] }}
                                </h2>
                            </div>
                        </div>
                        <span class="border rounded-circle p-4">
                            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 0.333344C7.88166 0.333344 0.499992 7.71501 0.499992 16.8333C0.499992 25.9517 7.88166 33.3333 17 33.3333C26.1183 33.3333 33.5 25.9517 33.5 16.8333C33.5 7.71501 26.1183 0.333344 17 0.333344ZM24.4167 17.6667H17.9167V24.1667H16.0833V17.6667H9.58332V15.8333H16.0833V9.33334H17.9167V15.8333H24.4167V17.6667Z"
                                    fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <!-- Add Balance -->
                <button type="button" class="btn btn-sm btn-success rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#addBalanceModal">
                    <i class="fas fa-plus-circle me-2"></i>Balance
                </button>

                <!-- Subtract Balance -->
                <button type="button" class="btn btn-sm btn-danger rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#subtractBalanceModal">
                    <i class="fas fa-minus-circle me-2"></i>Balance
                </button>

                <!-- Login History -->
                <a href="{{ route('admin.reports.login-history', ['user_id' => $user->id]) }}"
                    class="btn btn-sm btn-warning rounded-pill px-4">
                    <i class="fas fa-history me-2"></i>Logins
                </a>

                <!-- Login as User -->
                <button type="button" class="btn btn-sm btn-info rounded-pill px-4"
                    onclick="alert('Login as User - Coming Soon')">
                    <i class="fas fa-sign-in-alt me-2"></i>Login as User
                </button>

                <!-- User Tree -->
                <button type="button" class="btn btn-sm btn-success rounded-pill px-4"
                    onclick="alert('User Tree - Coming Soon')">
                    <i class="fas fa-sitemap me-2"></i>User Tree
                </button>

                <!-- Ban User -->
                <button type="button" class="btn btn-sm btn-warning rounded-pill px-4"
                    onclick="alert('Ban User - Coming Soon')">
                    <i class="fas fa-ban me-2"></i>Ban User
                </button>

                <!-- Change Password -->
                <button type="button" class="btn btn-sm btn-danger rounded-pill px-4" data-bs-toggle="modal"
                    data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-2"></i>Change Password
                </button>
            </div>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Information</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}"
                                class="rounded-circle" width="120" height="120">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                style="width: 120px; height: 120px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                        <p class="text-muted">@if($user->username){{ '@' . $user->username }}@else{{ $user->email }}@endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Email</label>
                        <p class="mb-0 fw-semibold">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Username</label>
                        <p class="mb-0 fw-semibold">{{ $user->username ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Referral Code</label>
                        <p class="mb-0 fw-semibold">{{ $user->referral_code }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Referred By</label>
                        <p class="mb-0 fw-semibold">
                            @if($user->referrer)
                                <a
                                    href="{{ route('admin.users.show', $user->referrer) }}">{{ $user->referrer->username ?? $user->referrer->email }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Total Referrals</label>
                        <p class="mb-0 fw-semibold">{{ $user->referrals->count() }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Member Since</label>
                        <p class="mb-0 fw-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Email Verified</label>
                        <p class="mb-0">
                            @if($user->email_verified_at)
                                <span class="badge badge-success">Verified</span>
                            @else
                                <span class="badge badge-warning">Not Verified</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <!-- Recent Deposits -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Recent Deposits</h4>
                    <a href="{{ route('admin.deposits.index', ['user_id' => $user->id]) }}"
                        class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Gateway</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->deposits()->latest()->take(5)->get() as $deposit)
                                    <tr>
                                        <td><small>{{ $deposit->order_number }}</small></td>
                                        <td>{{ $deposit->gateway->name ?? 'N/A' }}</td>
                                        <td>{{ $deposit->currency }} {{ number_format($deposit->amount, 2) }}</td>
                                        <td>
                                            @if($deposit->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($deposit->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($deposit->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($deposit->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $deposit->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No deposits found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Direct Referrals -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Direct Referrals ({{ $user->referrals->count() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->referrals()->latest()->take(10)->get() as $referral)
                                    <tr>
                                        <td>{{ $referral->name }}</td>
                                        <td>{{ $referral->username ?? 'N/A' }}</td>
                                        <td>{{ $referral->email }}</td>
                                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $referral) }}"
                                                class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No referrals found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Balance Modal -->
    <div class="modal fade" id="addBalanceModal" tabindex="-1" aria-labelledby="addBalanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBalanceModalLabel">Add Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.add-balance', $user) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">Add balance to user: <strong>{{ $user->name }}</strong></p>
                        <div class="mb-3">
                            <label for="add_amount" class="form-label">Amount (USDT)</label>
                            <input type="number" step="0.01" class="form-control" id="add_amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_note" class="form-label">Note (Optional)</label>
                            <textarea class="form-control" id="add_note" name="note" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Balance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Subtract Balance Modal -->
    <div class="modal fade" id="subtractBalanceModal" tabindex="-1" aria-labelledby="subtractBalanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subtractBalanceModalLabel">Subtract Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.subtract-balance', $user) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">Subtract balance from user: <strong>{{ $user->name }}</strong></p>
                        <div class="mb-3">
                            <label for="subtract_amount" class="form-label">Amount (USDT)</label>
                            <input type="number" step="0.01" class="form-control" id="subtract_amount" name="amount"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="subtract_note" class="form-label">Note (Optional)</label>
                            <textarea class="form-control" id="subtract_note" name="note" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Subtract Balance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.change-password', $user) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">Change password for user: <strong>{{ $user->name }}</strong></p>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="password_confirmation"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection