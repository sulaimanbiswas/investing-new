@extends('admin.layouts.app')

@section('title', 'Admin | Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <div class="row g-3 g-xl-4">
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-primary bg-opacity-10 text-primary">
                        <i class="la la-users fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Total Users</p>
                        <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-success bg-opacity-10 text-success">
                        <i class="la la-bolt fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Active Users</p>
                        <h4 class="mb-0">{{ number_format($activeUsers) }}</h4>
                        <small class="text-muted">Last 24h: {{ number_format($loginLast24h) }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-info bg-opacity-10 text-info">
                        <i class="la la-arrow-down fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Total Deposited</p>
                        <h4 class="mb-0">{{ number_format($totalDeposited, 2) }} {{ $currencySymbol }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-danger bg-opacity-10 text-danger">
                        <i class="la la-arrow-up fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Total Withdrawn</p>
                        <h4 class="mb-0">{{ number_format($totalWithdrawn, 2) }} {{ $currencySymbol }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-secondary bg-opacity-10 text-secondary">
                        <i class="la la-balance-scale fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Net Flow</p>
                        <h4 class="mb-0">{{ number_format($netFlow, 2) }} {{ $currencySymbol }}</h4>
                        <small class="text-muted">Deposits - Withdrawals</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-warning bg-opacity-10 text-warning">
                        <i class="la la-hourglass-half fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Pending Deposits</p>
                        <h4 class="mb-0">{{ number_format($pendingDeposits) }}</h4>
                        <small class="text-muted">Awaiting review</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-warning bg-opacity-10 text-warning">
                        <i class="la la-hourglass fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Pending Withdrawals</p>
                        <h4 class="mb-0">{{ number_format($pendingWithdrawals) }}</h4>
                        <small class="text-muted">Awaiting review</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-danger bg-opacity-10 text-danger">
                        <i class="la la-times-circle fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Rejected Deposits</p>
                        <h4 class="mb-0">{{ number_format($rejectedDeposits) }}</h4>
                        <small class="text-muted">Declined</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-danger bg-opacity-10 text-danger">
                        <i class="la la-ban fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Rejected Withdrawals</p>
                        <h4 class="mb-0">{{ number_format($rejectedWithdrawals) }}</h4>
                        <small class="text-muted">Declined</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-success bg-opacity-10 text-success">
                        <i class="la la-check-circle fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Deposit Approval Rate</p>
                        @php
                            $depositApprovalRate = $totalDepositRequests > 0
                                ? round(($approvedDepositsCount / $totalDepositRequests) * 100, 2)
                                : 0;
                        @endphp
                        <h4 class="mb-0">{{ $depositApprovalRate }}%</h4>
                        <small class="text-muted">{{ $approvedDepositsCount }} / {{ $totalDepositRequests }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-success bg-opacity-10 text-success">
                        <i class="la la-check fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Withdrawal Approval Rate</p>
                        @php
                            $withdrawApprovalRate = $totalWithdrawalRequests > 0
                                ? round(($approvedWithdrawalsCount / $totalWithdrawalRequests) * 100, 2)
                                : 0;
                        @endphp
                        <h4 class="mb-0">{{ $withdrawApprovalRate }}%</h4>
                        <small class="text-muted">{{ $approvedWithdrawalsCount }} / {{ $totalWithdrawalRequests }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 p-3 rounded bg-primary bg-opacity-10 text-primary">
                        <i class="la la-sign-in fs-26"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-muted">Logins (24h)</p>
                        <h4 class="mb-0">{{ number_format($loginLast24h) }}</h4>
                        <small class="text-muted">Successful logins</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-xl-4 mt-1">
        <div class="col-xl-8 col-lg-12">
            <div class="card h-100">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="card-title">Cash Flow (7d)</h4>
                        <small class="text-muted">Approved deposits vs withdrawals</small>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chart-flow" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Deposit Status</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart-deposit-status" height="240"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Withdrawal Status</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart-withdraw-status" height="240"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Pending Queue</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart-pending" height="240"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">User Activity</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart-activity" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 g-xl-4 mt-1">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Pending Deposits</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th class="text-end">Amount ({{ $currencySymbol }})</th>
                                    <th>Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingDepositRows as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? $item->user->username ?? 'User' }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                        <td>{{ optional($item->created_at)->format('M d, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No pending deposits</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Pending Withdrawals</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th class="text-end">Amount ({{ $currencySymbol }})</th>
                                    <th>Requested</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingWithdrawalRows as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? $item->user->username ?? 'User' }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                        <td>{{ optional($item->created_at)->format('M d, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No pending withdrawals</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const labels = @json($chartLabels);
        const depositSeries = @json($depositSeries);
        const withdrawalSeries = @json($withdrawalSeries);

        const primary = getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#36C95F';
        const palette = {
            primary: primary.trim() || '#36C95F',
            success: '#22c55e',
            danger: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6',
            muted: '#9ca3af'
        };

        const ctxFlow = document.getElementById('chart-flow');
        if (ctxFlow) {
            new Chart(ctxFlow, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Deposits',
                            data: depositSeries,
                            borderColor: palette.primary,
                            backgroundColor: Chart.helpers.color(palette.primary).alpha(0.15).rgbString(),
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Withdrawals',
                            data: withdrawalSeries,
                            borderColor: palette.info,
                            backgroundColor: Chart.helpers.color(palette.info).alpha(0.12).rgbString(),
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                        },
                    ],
                },
                options: {
                    plugins: { legend: { display: true } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => v.toLocaleString() } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        const depositStatus = [
                {{ $approvedDepositsCount }},
                {{ $pendingDeposits }},
            {{ $rejectedDeposits }}
        ];
        const withdrawStatus = [
                {{ $approvedWithdrawalsCount }},
                {{ $pendingWithdrawals }},
            {{ $rejectedWithdrawals }}
        ];

        function doughnut(elId, data, colors, labels) {
            const el = document.getElementById(elId);
            if (!el) return;
            new Chart(el, {
                type: 'doughnut',
                data: { datasets: [{ data, backgroundColor: colors }], labels },
                options: { cutout: '65%', plugins: { legend: { position: 'bottom' } } }
            });
        }

        doughnut('chart-deposit-status', depositStatus, [palette.primary, palette.warning, palette.danger], ['Approved', 'Pending', 'Rejected']);
        doughnut('chart-withdraw-status', withdrawStatus, [palette.success, palette.warning, palette.danger], ['Approved', 'Pending', 'Rejected']);

        const pendingCtx = document.getElementById('chart-pending');
        if (pendingCtx) {
            new Chart(pendingCtx, {
                type: 'bar',
                data: {
                    labels: ['Deposits', 'Withdrawals'],
                    datasets: [
                        {
                            label: 'Pending',
                            data: [{{ $pendingDeposits }}, {{ $pendingWithdrawals }}],
                            backgroundColor: palette.warning,
                        },
                        {
                            label: 'Rejected',
                            data: [{{ $rejectedDeposits }}, {{ $rejectedWithdrawals }}],
                            backgroundColor: palette.danger,
                        }
                    ]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        const activityCtx = document.getElementById('chart-activity');
        if (activityCtx) {
            new Chart(activityCtx, {
                type: 'bar',
                data: {
                    labels: ['Total Users', 'Active Users', 'Logins 24h'],
                    datasets: [
                        {
                            label: 'Users',
                            data: [{{ $totalUsers }}, {{ $activeUsers }}, {{ $loginLast24h }}],
                            backgroundColor: [palette.primary, palette.success, palette.info],
                        }
                    ]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    </script>
@endpush