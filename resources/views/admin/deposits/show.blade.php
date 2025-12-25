@extends('admin.layouts.app')

@section('title', 'Deposit Details')

@section('content')
    <div class="row">
        <div class="col-xl-8 col-xxl-8">
            <div class="card">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 text-white">
                        Deposit Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Deposit Header -->
                    <div class="media d-sm-flex d-block text-center text-sm-start pb-4 mb-4 border-bottom">
                        @if($deposit->gateway && $deposit->gateway->logo_path)
                            <img alt="{{ $deposit->gateway->name }}" class="rounded me-sm-4 me-0" width="130"
                                src="{{ asset($deposit->gateway->logo_path) }}">
                        @else
                            <div class="rounded me-sm-4 me-0 bg-primary d-inline-flex align-items-center justify-content-center"
                                style="width: 130px; height: 130px;">
                                <i class="fas fa-wallet fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="media-body align-items-center">
                            <div class="d-sm-flex d-block justify-content-between my-3 my-sm-0">
                                <div>
                                    <h3 class="fs-22 text-black font-w600 mb-0">{{ $deposit->gateway->name ?? 'N/A' }}</h3>
                                    <p class="mb-2 mb-sm-2">{{ $deposit->created_at->format('d F Y, h:i A') }}</p>
                                </div>
                                <div>
                                    <span class="badge badge-primary badge-lg">#{{ $deposit->order_number }}</span>
                                </div>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-primary light btn-rounded mb-2 me-2">
                                <svg class="me-2 scale5" width="14" height="14" viewBox="0 0 26 26" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13 0C5.8203 0 0 5.8203 0 13C0 20.1797 5.8203 26 13 26C20.1797 26 26 20.1797 26 13C26 5.8203 20.1797 0 13 0ZM13 23.4C7.2422 23.4 2.6 18.7578 2.6 13C2.6 7.2422 7.2422 2.6 13 2.6C18.7578 2.6 23.4 7.2422 23.4 13C23.4 18.7578 18.7578 23.4 13 23.4Z"
                                        fill="#2BC155" />
                                    <path
                                        d="M13 6.5C12.2812 6.5 11.7 7.0812 11.7 7.8V13.65L16.3172 16.3172C16.9266 16.6625 17.7109 16.4562 18.0562 15.8469C18.4016 15.2375 18.1953 14.4531 17.5859 14.1078L13.65 11.8344V7.8C13.65 7.0812 13.0688 6.5 13 6.5Z"
                                        fill="#2BC155" />
                                </svg>
                                <span class="text-primary">
                                    {{ ucfirst($deposit->status) }}
                                </span>
                            </a>
                            @if($deposit->status === 'approved')
                                <a href="javascript:void(0);" class="btn btn-success light btn-rounded mb-2">
                                    <svg class="me-2 scale5" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM10.5 17.25L5.25 12L6.6645 10.5855L10.5 14.4142L17.3355 7.57875L18.75 9L10.5 17.25Z"
                                            fill="#2BC155" />
                                    </svg>
                                    Approved
                                </a>
                            @elseif($deposit->status === 'pending')
                                <a href="javascript:void(0);" class="btn btn-warning light btn-rounded mb-2">
                                    <svg class="me-2 scale5" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM12 21.6C6.69355 21.6 2.4 17.3065 2.4 12C2.4 6.69355 6.69355 2.4 12 2.4C17.3065 2.4 21.6 6.69355 21.6 12C21.6 17.3065 17.3065 21.6 12 21.6Z"
                                            fill="#FFAA2B" />
                                        <path d="M13.2 6H10.8V13.2H13.2V6Z" fill="#FFAA2B" />
                                        <path d="M13.2 15.6H10.8V18H13.2V15.6Z" fill="#FFAA2B" />
                                    </svg>
                                    Pending Review
                                </a>
                            @elseif($deposit->status === 'rejected')
                                <a href="javascript:void(0);" class="btn btn-danger light btn-rounded mb-2">
                                    <svg class="me-2 scale5" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM16.2426 15.1815L15.1815 16.2426L12 13.0611L8.81853 16.2426L7.75743 15.1815L10.9389 12L7.75743 8.81853L8.81853 7.75743L12 10.9389L15.1815 7.75743L16.2426 8.81853L13.0611 12L16.2426 15.1815Z"
                                            fill="#FF5E5E" />
                                    </svg>
                                    Rejected
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Details Row -->
                    <div class="row">
                        <!-- Amount -->
                        <div class="col-lg-6 mb-3">
                            <div class="media align-items-start">
                                <span class="p-3 border border-primary-light rounded-circle me-3">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13.41 18.09V19H10.74V18.07C9.03 17.71 7.58 16.61 7.5 14.67H9.43C9.53 15.82 10.29 16.53 12.09 16.53C13.95 16.53 14.38 15.64 14.38 14.96C14.38 14.09 13.96 13.44 11.64 12.9C9.03 12.3 7.5 11.25 7.5 9.26C7.5 7.54 8.91 6.36 10.74 5.95V5H13.41V5.93C15.27 6.33 16.44 7.63 16.5 9.43H14.57C14.5 8.28 13.86 7.47 12.09 7.47C10.5 7.47 9.5 8.17 9.5 9.21C9.5 10.03 10.07 10.57 12.38 11.11C14.69 11.65 16.38 12.61 16.38 14.97C16.37 16.83 14.94 17.91 13.41 18.09Z"
                                            fill="#2BC155" />
                                    </svg>
                                </span>
                                <div class="media-body">
                                    <span class="d-block text-black font-w600 mb-1">Deposit Amount</span>
                                    <h4 class="mb-0 text-success">{{ number_format($deposit->amount, 2) }}
                                        {{ $deposit->currency }}
                                    </h4>
                                    @if($deposit->approved_amount && $deposit->approved_amount != $deposit->amount)
                                        <span class="badge badge-warning mt-2">
                                            Approved: {{ number_format($deposit->approved_amount, 2) }} {{ $deposit->currency }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Info -->
                        @if($deposit->user)
                            <div class="col-lg-6 mb-3">
                                <div class="media align-items-start">
                                    <span class="p-3 border border-primary-light rounded-circle me-3">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z"
                                                fill="#2BC155" />
                                        </svg>
                                    </span>
                                    <div class="media-body">
                                        <span class="d-block text-black font-w600 mb-1">User Details</span>
                                        <p class="mb-0">
                                            <strong>{{ $deposit->user->name }}</strong>
                                            <a href="{{ route('admin.users.show', $deposit->user)  }}">
                                                <span
                                                    class="text-primary">{{ '@' . ($deposit->user->username ?? $deposit->user->email) }}</span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <!-- Screenshot -->
                        @if($deposit->screenshot_path)
                            <div class="col-6 mb-3">
                                <div class="media align-items-start">
                                    <span class="p-3 border border-primary-light rounded-circle me-3">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21 19V5C21 3.9 20.1 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19ZM8.5 13.5L11 16.51L14.5 12L19 18H5L8.5 13.5Z"
                                                fill="#2BC155" />
                                        </svg>
                                    </span>
                                    <div class="media-body">
                                        <span class="d-block text-black font-w600 mb-2">Payment Screenshot</span>
                                        <a href="{{ asset($deposit->screenshot_path) }}" target="_blank">
                                            <img src="{{ asset($deposit->screenshot_path) }}" alt="Payment Screenshot"
                                                class="img-thumbnail"
                                                style="max-width: 100%; max-height: 300px; cursor: pointer;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <!-- Transaction ID -->
                        @if($deposit->txn_id)
                            <div class="col-lg-6 mb-3">
                                <div class="media align-items-start">
                                    <span class="p-3 border border-primary-light rounded-circle me-3">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 8H4V6H20V8ZM20 12H4V14H20V12ZM20 16H4V18H20V16Z" fill="#2BC155" />
                                        </svg>
                                    </span>
                                    <div class="media-body">
                                        <span class="d-block text-black font-w600 mb-1">Transaction ID</span>
                                        <p class="mb-0 font-monospace text-break">{{ $deposit->txn_id }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Admin Note -->
                        @if($deposit->admin_note)
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <strong>Admin Note:</strong> {{ $deposit->admin_note }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Quick Stats -->
            @if($deposit->user)
                <div class="card mt-3">
                    <div class="card-header border-0 pb-0">
                        <h4 class="fs-20 font-w600">User Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-primary-light rounded">
                                    <h2 class="fs-32 font-w600 text-primary mb-0">
                                        {{ $deposit->user->deposits ? $deposit->user->deposits->count() : 0 }}
                                    </h2>
                                    <span class="fs-14 text-muted">Total Deposits</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-success-light rounded">
                                    <h2 class="fs-32 font-w600 text-success mb-0">
                                        {{ $deposit->user->deposits ? $deposit->user->deposits->where('status', 'approved')->count() : 0 }}
                                    </h2>
                                    <span class="fs-14 text-muted">Approved</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-info-light rounded">
                                    <h2 class="fs-28 font-w600 text-info mb-0">
                                        @php
                                            $totalApproved = 0;
                                            if ($deposit->user->deposits) {
                                                foreach ($deposit->user->deposits->where('status', 'approved') as $dep) {
                                                    $totalApproved += $dep->approved_amount ?? $dep->amount;
                                                }
                                            }
                                        @endphp
                                        {{ number_format($totalApproved, 2) }}
                                        USDT
                                    </h2>
                                    <span class="fs-14 text-muted">Total Deposited Amount</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions -->
        <div class="col-xl-4 col-xxl-4">
            @if($deposit->status === 'pending' || $deposit->status === 'initialed')
                <!-- Approve Card -->
                <div class="card bg-success-light mb-3">
                    <div class="card-header bg-success text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-check-circle me-2"></i> Approve Deposit
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.deposits.update-status', $deposit->id) }}" id="approveForm">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">

                            <div class="form-group mb-3">
                                <label class="font-w600 text-black">Approved Amount (USDT)</label>
                                <input type="number" step="0.01" name="approved_amount" id="approved_amount"
                                    class="form-control" value="{{ $deposit->amount }}" placeholder="Enter approved amount">
                                <small class="text-muted">Default: {{ number_format($deposit->amount, 2) }} USDT</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-w600 text-black">Approval Note <span class="text-danger" id="noteRequired"
                                        style="display:none;">*</span></label>
                                <textarea name="admin_note" id="admin_note" class="form-control" rows="3"
                                    placeholder="Add approval note...">{{ $deposit->admin_note }}</textarea>
                                <small class="text-muted" id="noteHint">Optional - This note will be visible to the user</small>
                                <small class="text-danger" id="noteRequiredHint" style="display:none;">Required when amount is
                                    changed</small>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle me-2"></i> Approve Deposit
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject Card -->
                <div class="card bg-danger-light">
                    <div class="card-header bg-danger text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-times-circle me-2"></i> Reject Deposit
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.deposits.update-status', $deposit->id) }}"
                            onsubmit="return confirm('Are you sure you want to reject this deposit?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">

                            <div class="form-group mb-3">
                                <label class="font-w600 text-black">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea name="admin_note" class="form-control" rows="3" required
                                    placeholder="Please provide a reason for rejection...">{{ $deposit->admin_note }}</textarea>
                                <small class="text-danger">Required: Explain why this deposit is being rejected</small>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times-circle me-2"></i> Reject Deposit
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Status Display Card -->
                <div class="card">
                    <div
                        class="card-header {{ $deposit->status === 'approved' ? 'bg-success' : 'bg-danger' }} text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i
                                class="fas {{ $deposit->status === 'approved' ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                            {{ ucfirst($deposit->status) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-{{ $deposit->status === 'approved' ? 'success' : 'danger' }} mb-0">
                            <strong>Status:</strong> This deposit has been {{ $deposit->status }}.<br>
                            <strong>Date:</strong> {{ $deposit->updated_at->format('d M Y, h:i A') }}
                            @if($deposit->admin_note)
                                <hr>
                                <strong>Note:</strong> {{ $deposit->admin_note }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const approvedAmountInput = document.getElementById('approved_amount');
            const adminNoteTextarea = document.getElementById('admin_note');
            const noteRequired = document.getElementById('noteRequired');
            const noteHint = document.getElementById('noteHint');
            const noteRequiredHint = document.getElementById('noteRequiredHint');
            const approveForm = document.getElementById('approveForm');

            if (approvedAmountInput && adminNoteTextarea) {
                const originalAmount = parseFloat({{ $deposit->amount }});

                approvedAmountInput.addEventListener('input', function () {
                    const currentAmount = parseFloat(this.value);

                    if (currentAmount !== originalAmount && !isNaN(currentAmount)) {
                        // Amount changed - make note required
                        adminNoteTextarea.setAttribute('required', 'required');
                        noteRequired.style.display = 'inline';
                        noteHint.style.display = 'none';
                        noteRequiredHint.style.display = 'block';
                    } else {
                        // Amount same as original - make note optional
                        adminNoteTextarea.removeAttribute('required');
                        noteRequired.style.display = 'none';
                        noteHint.style.display = 'block';
                        noteRequiredHint.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush