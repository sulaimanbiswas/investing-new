@extends('admin.layouts.app')

@section('title', 'Admin | Order Requests')

@section('content')
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Order Request List</h4>
        </div>
        <div class="card-body">
            <form method="GET" class="row mb-3 g-2">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                        placeholder="Search by user/platform...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="default-select form-control form-control-sm wide">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive recentOrderTable">
                <table class="table verticle-middle table-responsive-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Platform</th>
                            <th>Requested Balance</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Processed By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderRequests as $orderRequest)
                            <tr>
                                <td>{{ $loop->iteration + ($orderRequests->currentPage() - 1) * $orderRequests->perPage() }}</td>
                                <td>
                                    <strong>{{ $orderRequest->user->name }}</strong><br>
                                    <small class="text-muted">{{ '@' . ($orderRequest->user->username ?? $orderRequest->user->email) }}</small>
                                </td>
                                <td>{{ $orderRequest->platform->name ?? 'N/A' }}</td>
                                <td><strong>${{ number_format((float) $orderRequest->requested_balance, 2) }}</strong></td>
                                <td>
                                    @if($orderRequest->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($orderRequest->status === 'accepted')
                                        <span class="badge badge-success">Accepted</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ optional($orderRequest->requested_at)->format('d M Y h:i A') }}</td>
                                <td>
                                    @if($orderRequest->processedBy)
                                        <span class="text-dark">{{ $orderRequest->processedBy->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.users.show', $orderRequest->user_id) }}" class="btn btn-info btn-xs light">
                                            View User
                                        </a>

                                        @if($orderRequest->status === 'pending')
                                            <form method="POST" action="{{ route('admin.order-requests.update-status', $orderRequest) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-success btn-xs">Accept</button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.order-requests.update-status', $orderRequest) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <input type="hidden" name="admin_note" value="Request rejected by admin">
                                                <button type="submit" class="btn btn-danger btn-xs">Reject</button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($orderRequest->admin_note)
                                        <p class="mb-0 mt-2 text-muted small">{{ $orderRequest->admin_note }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No order requests found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orderRequests->links() }}
            </div>
        </div>
    </div>
@endsection
