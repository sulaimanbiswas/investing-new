@extends('admin.layouts.app')

@section('title', 'Transaction Logs')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Transactions</a></li>
        </ol>
    </div>

    <div class="">

        <div class="row">
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-danger text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="8 12 12 16 16 12"></polyline>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Order Payments</p>
                                <h4 class="mb-0">{{ number_format($stats['order_payment'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-success text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="16 12 12 8 8 12"></polyline>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Order Profits</p>
                                <h4 class="mb-0">{{ number_format($stats['order_profit'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-info text-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Deposits</p>
                                <h4 class="mb-0">{{ number_format($stats['deposit'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-warning text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Withdrawals</p>
                                <h4 class="mb-0">{{ number_format($stats['withdrawal'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile: Accordion toggle button -->
        <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2" id="txnFilterAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header accordion-header-primary" id="txnFilterHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#txnFilterCollapse" aria-expanded="false" aria-controls="txnFilterCollapse">
                        Filter
                    </button>
                </h2>
                <div id="txnFilterCollapse" class="accordion-collapse collapse" aria-labelledby="txnFilterHeading"
                    data-bs-parent="#txnFilterAccordion">
                    <div class="accordion-body">
                        <form method="GET" class="row g-2">
                            <div class="col-12">
                                <select name="type" class="default-select form-control form-control-sm wide">
                                    <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="order_payment" {{ $typeFilter === 'order_payment' ? 'selected' : '' }}>
                                        Order Payment</option>
                                    <option value="order_profit" {{ $typeFilter === 'order_profit' ? 'selected' : '' }}>Order
                                        Profit</option>
                                    <option value="order_principal_return" {{ $typeFilter === 'order_principal_return' ? 'selected' : '' }}>Order Principal Return</option>
                                    <option value="deposit" {{ $typeFilter === 'deposit' ? 'selected' : '' }}>Deposit</option>
                                    <option value="withdrawal" {{ $typeFilter === 'withdrawal' ? 'selected' : '' }}>Withdrawal
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <input type="text" name="daterange"
                                    class="form-control form-control-sm input-daterange-datepicker"
                                    value="@if($startDate && $endDate){{ $startDate }} - {{ $endDate }}@endif"
                                    placeholder="Select Date Range">
                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" value="{{ $endDate }}">
                            </div>

                            <div class="col-12">
                                <select name="user_id" class="default-select form-control form-control-sm wide">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (string) $userId === (string) $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->username ?? $user->email }})
                                        </option>
                                    @endforeach
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
            <div class="col-md-3">
                <select name="type" class="default-select form-control form-control-sm wide">
                    <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="order_payment" {{ $typeFilter === 'order_payment' ? 'selected' : '' }}>Order Payment
                    </option>
                    <option value="order_profit" {{ $typeFilter === 'order_profit' ? 'selected' : '' }}>Order Profit</option>
                    <option value="order_principal_return" {{ $typeFilter === 'order_principal_return' ? 'selected' : '' }}>
                        Order Principal Return</option>
                    <option value="deposit" {{ $typeFilter === 'deposit' ? 'selected' : '' }}>Deposit</option>
                    <option value="withdrawal" {{ $typeFilter === 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="daterange" class="form-control form-control-sm input-daterange-datepicker"
                    value="@if($startDate && $endDate){{ $startDate }} - {{ $endDate }}@endif"
                    placeholder="Select Date Range">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
            </div>

            <div class="col-md-3">
                <select name="user_id" class="default-select form-control form-control-sm wide">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (string) $userId === (string) $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light btn-sm">Reset</a>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Recent Transactions</h4>
            </div>
            <div class="card-body">

                <div class="table-responsive recentOrderTable">
                    <table class="table verticle-middle table-responsive-md">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Type</th>
                                <th>Transacted</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Before</th>
                                <th class="text-end">After</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $txn)
                                <tr>
                                    <td class="fw-semibold text-dark">
                                        @if($txn->user)
                                            <strong>{{ $txn->user->name }}</strong><br>
                                            <small class="text-muted">{{ '@' . ($txn->user->username ?? $txn->user->email) }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match ($txn->type) {
                                                'order_payment' => 'bg-danger',
                                                'order_profit' => 'bg-success',
                                                'order_principal_return' => 'bg-primary',
                                                'deposit' => 'bg-info',
                                                'withdrawal' => 'bg-warning',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $badgeClass }}">
                                            {{ ucwords(str_replace('_', ' ', $txn->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $txn->created_at?->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $txn->created_at?->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end fw-semibold {{ $txn->amount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ ($txn->amount >= 0 ? '+' : '') . number_format($txn->amount, 2) }} USDT
                                    </td>
                                    <td class="text-end text-muted">
                                        {{ number_format($txn->balance_before, 2) }}
                                    </td>
                                    <td class="text-end text-muted">
                                        {{ number_format($txn->balance_after, 2) }}
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $txn->remarks }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $records->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.input-daterange-datepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('.input-daterange-datepicker').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                var form = $(this).closest('form');
                form.find('input[name="start_date"]').remove();
                form.find('input[name="end_date"]').remove();
                $('<input>').attr({ type: 'hidden', name: 'start_date', value: picker.startDate.format('YYYY-MM-DD') }).appendTo(form);
                $('<input>').attr({ type: 'hidden', name: 'end_date', value: picker.endDate.format('YYYY-MM-DD') }).appendTo(form);
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