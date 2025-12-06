@extends('admin.layouts.app')

@section('title', 'Admin | Withdrawals')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Withdrawals</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="">

        <div class="row">
            <div class="col-xl-4 col-xxl-4 col-sm-6">
                <div class="card gradient-bx text-white bg-success">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <p class="mb-1">Approved Withdrawal</p>
                                <div class="d-flex flex-wrap">
                                    <h2 class="fs-40 font-w600 text-white mb-0 me-3">
                                        {{ number_format($stats['approved'], 2) }}
                                    </h2>
                                    <div>
                                        <svg width="28" height="19" viewBox="0 0 28 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.56244 9.25C6.35869 11.6256 2.26214 16.0091 0.999939 17.5H26.4374V1L16.8124 13.375L8.56244 9.25Z"
                                                fill="url(#paint0_linear1)" />
                                            <path
                                                d="M0.999939 17.5C2.26214 16.0091 6.35869 11.6256 8.56244 9.25L16.8124 13.375L26.4374 1"
                                                stroke="white" stroke-width="2" />
                                            <defs>
                                                <linearGradient id="paint0_linear1" x1="13.7187" y1="3.0625" x2="14.7499"
                                                    y2="17.5" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="white" stop-opacity="0.73" offset="0.1" />
                                                    <stop offset="1" stop-color="white" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <span class="border rounded-circle p-4">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 0.333344C7.88166 0.333344 0.499992 7.71501 0.499992 16.8333C0.499992 25.9517 7.88166 33.3333 17 33.3333C26.1183 33.3333 33.5 25.9517 33.5 16.8333C33.5 7.71501 26.1183 0.333344 17 0.333344ZM24.145 13.9783L15.8117 22.3117C15.5867 22.5367 15.2933 22.6667 14.9817 22.6667C14.67 22.6667 14.3767 22.5367 14.1517 22.3117L9.85499 18.015C9.38666 17.5467 9.38666 16.7867 9.85499 16.3183C10.3233 15.85 11.0833 15.85 11.5517 16.3183L14.9817 19.7483L22.4483 12.2817C22.9167 11.8133 23.6767 11.8133 24.145 12.2817C24.6133 12.75 24.6133 13.51 24.145 13.9783Z"
                                        fill="white" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-4 col-sm-6">
                <div class="card gradient-bx text-white bg-warning">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <p class="mb-1">Pending Withdrawal</p>
                                <div class="d-flex flex-wrap">
                                    <h2 class="fs-40 font-w600 text-white mb-0 me-3">
                                        {{ number_format($stats['pending'], 2) }}
                                    </h2>
                                    <div>
                                        <svg width="28" height="19" viewBox="0 0 28 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.56244 9.25C6.35869 11.6256 2.26214 16.0091 0.999939 17.5H26.4374V1L16.8124 13.375L8.56244 9.25Z"
                                                fill="url(#paint0_linear2)" />
                                            <path
                                                d="M0.999939 17.5C2.26214 16.0091 6.35869 11.6256 8.56244 9.25L16.8124 13.375L26.4374 1"
                                                stroke="white" stroke-width="2" />
                                            <defs>
                                                <linearGradient id="paint0_linear2" x1="13.7187" y1="3.0625" x2="14.7499"
                                                    y2="17.5" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="white" stop-opacity="0.73" offset="0.1" />
                                                    <stop offset="1" stop-color="white" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <span class="border rounded-circle p-4">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 0.333344C7.88166 0.333344 0.499992 7.71501 0.499992 16.8333C0.499992 25.9517 7.88166 33.3333 17 33.3333C26.1183 33.3333 33.5 25.9517 33.5 16.8333C33.5 7.71501 26.1183 0.333344 17 0.333344ZM17 29.6667C9.89832 29.6667 4.16666 23.935 4.16666 16.8333C4.16666 9.73168 9.89832 4.00001 17 4.00001C24.1017 4.00001 29.8333 9.73168 29.8333 16.8333C29.8333 23.935 24.1017 29.6667 17 29.6667ZM17.9167 9.50001H15.25V18.6667L23.0833 23.2833L24.4167 21.1517L17.9167 17.2083V9.50001Z"
                                        fill="white" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-4 col-sm-6">
                <div class="card gradient-bx text-white bg-danger">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <p class="mb-1">Rejected Withdrawal</p>
                                <div class="d-flex flex-wrap">
                                    <h2 class="fs-40 font-w600 text-white mb-0 me-3">
                                        {{ number_format($stats['rejected'], 2) }}
                                    </h2>
                                    <div>
                                        <svg width="28" height="19" viewBox="0 0 28 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M8.56244 9.25C6.35869 11.6256 2.26214 16.0091 0.999939 17.5H26.4374V1L16.8124 13.375L8.56244 9.25Z"
                                                fill="url(#paint0_linear3)" />
                                            <path
                                                d="M0.999939 17.5C2.26214 16.0091 6.35869 11.6256 8.56244 9.25L16.8124 13.375L26.4374 1"
                                                stroke="white" stroke-width="2" />
                                            <defs>
                                                <linearGradient id="paint0_linear3" x1="13.7187" y1="3.0625" x2="14.7499"
                                                    y2="17.5" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="white" stop-opacity="0.73" offset="0.1" />
                                                    <stop offset="1" stop-color="white" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <span class="border rounded-circle p-4">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 0.333344C7.88166 0.333344 0.499992 7.71501 0.499992 16.8333C0.499992 25.9517 7.88166 33.3333 17 33.3333C26.1183 33.3333 33.5 25.9517 33.5 16.8333C33.5 7.71501 26.1183 0.333344 17 0.333344ZM23.0833 21.235L21.235 23.0833L17 18.8483L12.765 23.0833L10.9167 21.235L15.1517 16.8333L10.9167 12.6317L12.765 10.7833L17 15.0183L21.235 10.7833L23.0833 12.6317L18.8483 16.8333L23.0833 21.235Z"
                                        fill="white" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile: Accordion toggle button -->
        <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2" id="withdrawalFilterAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header accordion-header-primary" id="withdrawalFilterHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#withdrawalFilterCollapse" aria-expanded="false"
                        aria-controls="withdrawalFilterCollapse">
                        Filter
                    </button>
                </h2>
                <div id="withdrawalFilterCollapse" class="accordion-collapse collapse"
                    aria-labelledby="withdrawalFilterHeading" data-bs-parent="#withdrawalFilterAccordion">
                    <div class="accordion-body">
                        <form method="GET" class="row g-2">
                            <div class="col-12">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm"
                                    placeholder="Search by order number, wallet address, user...">
                            </div>

                            <div class="col-12">
                                <input type="text" name="daterange"
                                    class="form-control form-control-sm input-daterange-datepicker"
                                    value="@if(request('start_date') && request('end_date')){{ request('start_date') }} - {{ request('end_date') }}@endif"
                                    placeholder="Select Date Range">
                            </div>

                            <div class="col-12">
                                <select name="user_id" class="default-select form-control form-control-sm wide">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->username ?? $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <select name="status" class="default-select form-control form-control-sm wide">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                    </option>
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
                    placeholder="Search...">
            </div>

            <div class="col-md-3">
                <input type="text" name="daterange" class="form-control form-control-sm input-daterange-datepicker"
                    value="@if(request('start_date') && request('end_date')){{ request('start_date') }} - {{ request('end_date') }}@endif"
                    placeholder="Select Date Range">
            </div>

            <div class="col-md-3">
                <select name="user_id" class="default-select form-control form-control-sm wide">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1">
                <select name="status" class="default-select form-control form-control-sm wide">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-1 d-grid">
                <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order Number</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Wallet Address</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end" style="width: 80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $withdrawal->order_number }}</strong>
                                    </td>
                                    <td>
                                        @if($withdrawal->user)
                                            <strong>{{ $withdrawal->user->name }}</strong><br>
                                            <small
                                                class="text-muted">{{ '@' . ($withdrawal->user->username ?? $withdrawal->user->email) }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $withdrawal->currency }} {{ number_format($withdrawal->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <small class="font-monospace">{{ Str::limit($withdrawal->wallet_address, 20) }}</small>
                                    </td>
                                    <td>
                                        {{ $withdrawal->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($withdrawal->status === 'approved')
                                            <span class="badge light badge-success">Approved</span>
                                        @elseif($withdrawal->status === 'pending')
                                            <span class="badge light badge-warning">Pending</span>
                                        @elseif($withdrawal->status === 'rejected')
                                            <span class="badge light badge-danger">Rejected</span>
                                        @else
                                            <span class="badge light badge-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button type="button"
                                                class="btn {{ $withdrawal->status === 'approved' ? 'btn-success' : ($withdrawal->status === 'pending' ? 'btn-warning' : ($withdrawal->status === 'rejected' ? 'btn-danger' : 'btn-secondary')) }} light sharp"
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
                                                    href="{{ route('admin.withdrawals.show', $withdrawal) }}">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No withdrawals found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $withdrawals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize daterangepicker
            $('.input-daterange-datepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

                // Create hidden inputs for start_date and end_date
                var form = $(this).closest('form');
                form.find('input[name="start_date"]').remove();
                form.find('input[name="end_date"]').remove();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'start_date',
                    value: picker.startDate.format('YYYY-MM-DD')
                }).appendTo(form);

                $('<input>').attr({
                    type: 'hidden',
                    name: 'end_date',
                    value: picker.endDate.format('YYYY-MM-DD')
                }).appendTo(form);
            });

            $('.input-daterange-datepicker').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                var form = $(this).closest('form');
                form.find('input[name="start_date"]').remove();
                form.find('input[name="end_date"]').remove();
            });
        });
    </script>
@endpush