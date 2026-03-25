@extends('admin.layouts.app')

@section('title', 'Admin | Order Requests')

@section('content')
    <style>
        .order-requests-table thead th {
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #6c757d;
            white-space: nowrap;
            border-bottom-width: 1px;
        }

        .order-requests-table tbody td {
            vertical-align: middle;
            padding-top: 14px;
            padding-bottom: 14px;
        }

        /* .order-requests-user {
                min-width: 210px;
            } */

        .order-requests-platform {
            min-width: 150px;
        }

        .request-id-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 24px;
            padding: 0 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background: #f5f6fa;
            color: #3f4254;
        }

        .actions-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.1;
        }

        .action-btn i {
            font-size: 10px;
        }

        .filter-card {
            border: 1px solid #eceef3;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 16px;
            background: #fcfcfe;
        }
    </style>

    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Order Requests</a></li>
        </ol>
    </div>

    @if(session('error'))
        <div class="alert alert-danger solid alert-dismissible fade show">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger solid alert-dismissible fade show">
            <strong>{{ $errors->first() }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4 col-xxl-4 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Pending Requests</p>
                            <h3 class="font-w600 text-white mb-0">{{ number_format($stats['pending']) }}</h3>
                        </div>
                        <span class="badge light badge-warning fs-14">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-xxl-4 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Accepted Requests</p>
                            <h3 class="font-w600 text-white mb-0">{{ number_format($stats['accepted']) }}</h3>
                        </div>
                        <span class="badge light badge-success fs-14">Accepted</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-xxl-4 col-sm-6">
            <div class="card gradient-bx text-white" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Rejected Requests</p>
                            <h3 class="font-w600 text-white mb-0">{{ number_format($stats['rejected']) }}</h3>
                        </div>
                        <span class="badge light badge-danger fs-14">Rejected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap gap-2 justify-content-between align-items-center border-bottom">
            <div>
                <h4 class="card-title mb-0">Order Request List</h4>
                <small class="text-muted">Manage request approval flow from one place</small>
            </div>
            <span class="badge badge-light text-dark px-3 py-2">Total: {{ number_format($orderRequests->total()) }}</span>
        </div>
        <div class="card-body">
            <form method="GET" class="filter-card">
                <div class="row g-2 align-items-end">
                    <div class="col-xl-5 col-md-6">
                        <label class="form-label mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm"
                            placeholder="Search by user, username, email or platform...">
                    </div>
                    <div class="col-xl-3 col-md-4">
                        <label class="form-label mb-1">Status</label>
                        <select name="status" class="default-select form-control form-control-sm wide">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>
                    <div class="col-xl-4 col-md-12 d-flex flex-wrap gap-2">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fa fa-search me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('admin.order-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-rotate-left me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive recentOrderTable border rounded">
                <table class="table table-hover table-striped align-middle mb-0 order-requests-table">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Platform</th>
                            <th>Requested Balance</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Processed By</th>
                            <th>
                                Description
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderRequests as $orderRequest)
                            <tr>
                                <td>
                                    <span class="request-id-chip">
                                        {{ $loop->iteration + ($orderRequests->currentPage() - 1) * $orderRequests->perPage() }}
                                    </span>
                                </td>
                                <td class="order-requests-user">
                                    <div class="fw-semibold text-dark">{{ $orderRequest->user->name }}</div>
                                    <small
                                        class="text-muted">{{ '@' . ($orderRequest->user->username ?? $orderRequest->user->email) }}</small>
                                </td>
                                <td class="order-requests-platform">
                                    @if($orderRequest->platform)
                                        <span class="badge badge-light text-dark border">{{ $orderRequest->platform->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <strong
                                        class="text-primary">${{ number_format((float) $orderRequest->requested_balance, 2) }}</strong>
                                </td>
                                <td>
                                    @if($orderRequest->status === 'pending')
                                        <span class="badge badge-warning light">Pending</span>
                                    @elseif($orderRequest->status === 'accepted')
                                        <span class="badge badge-success light">Accepted</span>
                                    @else
                                        <span class="badge badge-danger light">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark">{{ optional($orderRequest->requested_at)->format('d M Y') }}</div>
                                    <small
                                        class="text-muted">{{ optional($orderRequest->requested_at)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($orderRequest->processedBy)
                                        <div class="text-dark">{{ $orderRequest->processedBy->name }}</div>
                                        <small
                                            class="text-muted">{{ optional($orderRequest->processed_at)->format('d M Y h:i A') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($orderRequest->admin_note)
                                        <p class="mb-0 mt-2 text-muted small">
                                            </i>{{ $orderRequest->admin_note }}
                                        </p>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-stack">
                                        <a href="{{ route('admin.users.show', $orderRequest->user_id) }}"
                                            class="btn btn-info btn-xs light action-btn" title="View User">
                                            <i class="fa fa-user"></i>
                                            View
                                        </a>

                                        @if($orderRequest->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('admin.order-requests.update-status', $orderRequest) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-success btn-xs action-btn"
                                                    title="Accept Request">
                                                    <i class="fa fa-check"></i>
                                                    Accept
                                                </button>
                                            </form>

                                            <form method="POST"
                                                action="{{ route('admin.order-requests.update-status', $orderRequest) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <input type="hidden" name="admin_note" value="Request rejected by admin">
                                                <button type="submit" class="btn btn-danger btn-xs action-btn"
                                                    title="Reject Request">
                                                    <i class="fa fa-times"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="fa fa-inbox fs-24 text-muted"></i>
                                    </div>
                                    <div class="fw-semibold">No order requests found</div>
                                    <small class="text-muted">Try changing search/filter and check again.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $orderRequests->links() }}
            </div>
        </div>
    </div>
@endsection