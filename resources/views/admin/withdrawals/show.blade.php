@extends('admin.layouts.app')

@section('title', 'Withdrawal Details')

@section('content')
    <div class="row">
        <div class="col-xl-8 col-xxl-8">
            <div class="card">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 text-white">
                        Withdrawal Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Withdrawal Header -->
                    <div class="media d-sm-flex d-block text-center text-sm-start pb-4 mb-4 border-bottom">
                        <div class="rounded me-sm-4 me-0 bg-primary d-inline-flex align-items-center justify-content-center"
                            style="width: 130px; height: 130px;">
                            <i class="fas fa-money-bill-wave fa-3x text-white"></i>
                        </div>
                        <div class="media-body align-items-center">
                            <div class="d-sm-flex d-block justify-content-between my-3 my-sm-0">
                                <div>
                                    <h3 class="fs-22 text-black font-w600 mb-0">Withdrawal Request</h3>
                                    <p class="mb-2 mb-sm-2">{{ $withdrawal->created_at->format('d F Y, h:i A') }}</p>
                                </div>
                                <div>
                                    <span class="badge badge-primary badge-lg">#{{ $withdrawal->order_number }}</span>
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
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </a>
                            @if($withdrawal->status === 'approved')
                                <a href="javascript:void(0);" class="btn btn-success light btn-rounded mb-2">
                                    <svg class="me-2 scale5" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM10.5 17.25L5.25 12L6.6645 10.5855L10.5 14.4142L17.3355 7.57875L18.75 9L10.5 17.25Z"
                                            fill="#2BC155" />
                                    </svg>
                                    Approved
                                </a>
                            @elseif($withdrawal->status === 'pending')
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
                            @elseif($withdrawal->status === 'rejected')
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
                                    <span class="d-block text-black font-w600 mb-1">Withdrawal Amount</span>
                                    <h4 class="mb-0 text-danger">{{ number_format($withdrawal->amount, 2) }}
                                        {{ $withdrawal->currency }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <!-- User Info -->
                        @if($withdrawal->user)
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
                                            <strong>{{ $withdrawal->user->name }}</strong>
                                            <span
                                                class="text-muted">{{ '@' . ($withdrawal->user->username ?? $withdrawal->user->email) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Wallet Address -->
                        <div class="col-12 mb-3">
                            <div class="media align-items-start">
                                <span class="p-3 border border-primary-light rounded-circle me-3">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21 18V19C21 20.1 20.1 21 19 21H5C3.89 21 3 20.1 3 19V5C3 3.9 3.89 3 5 3H19C20.1 3 21 3.9 21 5V6H12C10.89 6 10 6.9 10 8V16C10 17.1 10.89 18 12 18H21ZM12 16H22V8H12V16ZM16 13.5C15.17 13.5 14.5 12.83 14.5 12C14.5 11.17 15.17 10.5 16 10.5C16.83 10.5 17.5 11.17 17.5 12C17.5 12.83 16.83 13.5 16 13.5Z"
                                            fill="#2BC155" />
                                    </svg>
                                </span>
                                <div class="media-body">
                                    <span class="d-block text-black font-w600 mb-2">Wallet Address
                                        ({{ $withdrawal->currency }})</span>
                                    <p class="mb-0 font-monospace text-break">{{ $withdrawal->wallet_address }}</p>
                                    <button class="btn btn-sm btn-primary mt-2"
                                        onclick="copyToClipboard('{{ $withdrawal->wallet_address }}')">
                                        <i class="fas fa-copy me-1"></i> Copy Address
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Custom Fields -->
                        @if(!empty($withdrawal->custom_data))
                            <div class="col-12 mb-3">
                                <div class="media align-items-start">
                                    <span class="p-3 border border-primary-light rounded-circle me-3">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 5H21V7H3V5ZM3 17H21V19H3V17ZM3 11H21V13H3V11Z" fill="#2BC155" />
                                        </svg>
                                    </span>
                                    <div class="media-body w-100">
                                        <span class="d-block text-black font-w600 mb-2">Submitted Details</span>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <tbody>
                                                    @foreach($withdrawal->custom_data as $field => $value)
                                                        @php
                                                            $displayValue = is_array($value)
                                                                ? implode(', ', array_filter($value, fn($v) => $v !== null && $v !== ''))
                                                                : $value;

                                                            $path = is_string($displayValue) ? parse_url($displayValue, PHP_URL_PATH) : null;
                                                            $isImage = is_string($displayValue)
                                                                && $path
                                                                && preg_match('/\.(png|jpe?g|gif|webp|svg)$/i', $path)
                                                                && (\Illuminate\Support\Str::startsWith($displayValue, ['http://', 'https://', '/', 'storage', 'uploads']));
                                                        @endphp
                                                        <tr>
                                                            <th class="w-50 text-capitalize">{{ $field }}</th>
                                                            <td class="font-monospace text-break">
                                                                @if($isImage)
                                                                    <img src="{{ asset($displayValue) }}" alt="{{ $field }}"
                                                                        class="img-fluid rounded"
                                                                        style="max-width: 240px; max-height: 240px; object-fit: contain;">
                                                                @elseif($displayValue === null || $displayValue === '')
                                                                    <span class="text-muted">—</span>
                                                                @else
                                                                    {{ $displayValue }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Admin Note -->
                        @if($withdrawal->admin_note)
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <strong>Admin Note:</strong> {{ $withdrawal->admin_note }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Quick Stats -->
            @if($withdrawal->user)
                <div class="card mt-3">
                    <div class="card-header border-0 pb-0">
                        <h4 class="fs-20 font-w600">User Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-primary-light rounded">
                                    <h2 class="fs-32 font-w600 text-primary mb-0">
                                        {{ $withdrawal->user->withdrawals ? $withdrawal->user->withdrawals->count() : 0 }}
                                    </h2>
                                    <span class="fs-14 text-muted">Total Withdrawals</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-success-light rounded">
                                    <h2 class="fs-32 font-w600 text-success mb-0">
                                        {{ $withdrawal->user->withdrawals ? $withdrawal->user->withdrawals->where('status', 'approved')->count() : 0 }}
                                    </h2>
                                    <span class="fs-14 text-muted">Approved</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-danger-light rounded">
                                    <h2 class="fs-28 font-w600 text-danger mb-0">
                                        @php
                                            $totalApproved = 0;
                                            if ($withdrawal->user->withdrawals) {
                                                foreach ($withdrawal->user->withdrawals->where('status', 'approved') as $w) {
                                                    $totalApproved += $w->amount;
                                                }
                                            }
                                        @endphp
                                        {{ number_format($totalApproved, 2) }}
                                        USDT
                                    </h2>
                                    <span class="fs-14 text-muted">Total Withdrawn Amount</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions -->
        <div class="col-xl-4 col-xxl-4">
            @if($withdrawal->status === 'pending')
                <!-- Approve Card -->
                <div class="card bg-success-light mb-3">
                    <div class="card-header bg-success text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-check-circle me-2"></i> Approve Withdrawal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.withdrawals.update-status', $withdrawal->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">

                            <div class="alert alert-warning">
                                <strong>Amount:</strong> {{ number_format($withdrawal->amount, 2) }}
                                {{ $withdrawal->currency }}<br>
                                <small>This amount will be deducted from user's balance</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-w600 text-black">Approval Note</label>
                                <textarea name="admin_note" class="form-control" rows="3"
                                    placeholder="Add approval note (optional)...">{{ $withdrawal->admin_note }}</textarea>
                                <small class="text-muted">Optional - This note will be visible to the user</small>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle me-2"></i> Approve Withdrawal
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject Card -->
                <div class="card bg-danger-light">
                    <div class="card-header bg-danger text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-times-circle me-2"></i> Reject Withdrawal
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.withdrawals.update-status', $withdrawal->id) }}"
                            onsubmit="return confirm('Are you sure you want to reject this withdrawal?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">

                            <div class="form-group mb-3">
                                <label class="font-w600 text-black">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea name="admin_note" class="form-control" rows="3" required
                                    placeholder="Please provide a reason for rejection...">{{ $withdrawal->admin_note }}</textarea>
                                <small class="text-danger">Required: Explain why this withdrawal is being rejected</small>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times-circle me-2"></i> Reject Withdrawal
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Status Display Card -->
                <div class="card">
                    <div
                        class="card-header {{ $withdrawal->status === 'approved' ? 'bg-success' : 'bg-danger' }} text-white border-0">
                        <h5 class="mb-0 text-white">
                            <i
                                class="fas {{ $withdrawal->status === 'approved' ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                            {{ ucfirst($withdrawal->status) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-{{ $withdrawal->status === 'approved' ? 'success' : 'danger' }} mb-0">
                            <strong>Status:</strong> This withdrawal has been {{ $withdrawal->status }}.<br>
                            <strong>Date:</strong> {{ $withdrawal->updated_at->format('d M Y, h:i A') }}
                            @if($withdrawal->admin_note)
                                <hr>
                                <strong>Note:</strong> {{ $withdrawal->admin_note }}
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                alert('Wallet address copied to clipboard!');
            }, function (err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endpush