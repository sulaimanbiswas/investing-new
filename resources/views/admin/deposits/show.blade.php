@extends('admin.layouts.app')

@section('title', 'Deposit Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Deposit Details</h4>
                        <a href="{{ route('admin.deposits.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column - Deposit Info -->
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Deposit Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="200">Order Number:</th>
                                                <td><strong>{{ $deposit->order_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Gateway:</th>
                                                <td>{{ $deposit->gateway->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount:</th>
                                                <td><strong class="text-success">{{ number_format($deposit->amount, 2) }}
                                                        {{ $deposit->currency }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    @if($deposit->status === 'completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @elseif($deposit->status === 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($deposit->status === 'rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary">{{ ucfirst($deposit->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At:</th>
                                                <td>{{ $deposit->created_at->format('Y-m-d h:i A') }}
                                                    ({{ $deposit->created_at->diffForHumans() }})</td>
                                            </tr>
                                            <tr>
                                                <th>Updated At:</th>
                                                <td>{{ $deposit->updated_at->format('Y-m-d h:i A') }}
                                                    ({{ $deposit->updated_at->diffForHumans() }})</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- User Info -->
                                @if($deposit->user)
                                    <div class="card mb-3">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">User Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="200">Name:</th>
                                                    <td>{{ $deposit->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Username:</th>
                                                    <td>{{ '@' . ($deposit->user->username ?? 'N/A') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $deposit->user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Balance:</th>
                                                    <td><strong>{{ number_format($deposit->user->balance ?? 0, 2) }}
                                                            USDT</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Transaction Details -->
                                @if($deposit->txn_id || $deposit->screenshot_path)
                                    <div class="card mb-3">
                                        <div class="card-header bg-warning text-white">
                                            <h5 class="mb-0">Transaction Proof</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($deposit->txn_id)
                                                <div class="mb-3">
                                                    <strong>Transaction ID:</strong>
                                                    <p class="font-monospace">{{ $deposit->txn_id }}</p>
                                                </div>
                                            @endif

                                            @if($deposit->screenshot_path)
                                                <div>
                                                    <strong>Payment Screenshot:</strong>
                                                    <div class="mt-2">
                                                        <a href="{{ asset($deposit->screenshot_path) }}" target="_blank">
                                                            <img src="{{ asset($deposit->screenshot_path) }}"
                                                                alt="Payment Screenshot" class="img-thumbnail"
                                                                style="max-width: 400px; max-height: 400px;">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($deposit->admin_note)
                                    <div class="alert alert-info">
                                        <strong>Admin Note:</strong>
                                        <p class="mb-0">{{ $deposit->admin_note }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Right Column - Actions -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="mb-0">Update Status</h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST"
                                            action="{{ route('admin.deposits.update-status', $deposit->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <div class="form-group mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="pending" {{ $deposit->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="completed" {{ $deposit->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="rejected" {{ $deposit->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label>Admin Note</label>
                                                <textarea name="admin_note" class="form-control" rows="4"
                                                    placeholder="Add a note...">{{ $deposit->admin_note }}</textarea>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-save"></i> Update Status
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Quick Stats -->
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-3">Quick Stats</h6>
                                        <div class="mb-2">
                                            <small class="text-muted">User Total Deposits:</small>
                                            <br><strong>{{ $deposit->user && $deposit->user->deposits ? $deposit->user->deposits->count() : 0 }}</strong>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">User Total Deposited:</small>
                                            <br><strong>{{ $deposit->user && $deposit->user->deposits ? number_format($deposit->user->deposits->where('status', 'completed')->sum('amount'), 2) : '0.00' }} USDT</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection