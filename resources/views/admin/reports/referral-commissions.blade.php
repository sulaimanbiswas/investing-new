@extends('admin.layouts.app')

@section('title', 'Referral Commission Report')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Reports</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Referral Commissions</a></li>
        </ol>
    </div>

    <div class="">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-success text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-award">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.89"></polyline>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Total Commissions</p>
                                <h4 class="mb-0">USDT {{ number_format($stats['total_commissions'], 2) }}</h4>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trending-up">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Level 1 Commissions</p>
                                <h4 class="mb-0">USDT {{ number_format($stats['level1_commissions'], 2) }}</h4>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trending-up">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Level 2 Commissions</p>
                                <h4 class="mb-0">USDT {{ number_format($stats['level2_commissions'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-4 col-lg-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-danger text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trending-up">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Level 3 Commissions</p>
                                <h4 class="mb-0">USDT {{ number_format($stats['level3_commissions'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="row mb-3 g-2 d-none d-md-flex">
            <div class="col-md-3">
                <select name="user_id" class="default-select form-control form-control-sm wide">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->username ?? $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="level" class="default-select form-control form-control-sm wide">
                    <option value="">All Levels</option>
                    <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Level 1</option>
                    <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Level 2</option>
                    <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>Level 3</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="daterange" class="form-control form-control-sm input-daterange-datepicker"
                    value="@if(request('start_date') && request('end_date')){{ request('start_date') }} - {{ request('end_date') }}@endif"
                    placeholder="Select Date Range">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
                <a href="{{ route('admin.reports.referral-commissions') }}" class="btn btn-light btn-sm">Reset</a>
            </div>
        </form>

        <!-- Commission Table -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Commission Records</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive recentOrderTable">
                    <table class="table verticle-middle table-responsive-md">
                        <thead>
                            <tr>
                                <th>Earner</th>
                                <th>Referred User</th>
                                <th>Level</th>
                                <th>Deposit Amount</th>
                                <th>Commission %</th>
                                <th class="text-end">Commission Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commissions as $commission)
                                                    <tr>
                                                        <td class="fw-semibold text-dark">
                                                            @if($commission->user)
                                                                <strong>{{ $commission->user->name }}</strong><br>
                                                                <small
                                                                    class="text-muted">{{ '@' . ($commission->user->username ?? $commission->user->email) }}</small>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="fw-semibold text-dark">
                                                            @if($commission->referredUser)
                                                                <a href="{{ route('admin.users.show', $commission->referredUser) }}"
                                                                    class="text-primary">
                                                                    {{ $commission->referredUser->name }}
                                                                </a><br>
                                                                <small
                                                                    class="text-muted">{{ '@' . ($commission->referredUser->username ?? $commission->referredUser->email) }}</small>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $levelColors = [
                                                                    1 => 'bg-primary',
                                                                    2 => 'bg-warning',
                                                                    3 => 'bg-danger',
                                                                ];
                                                            @endphp
                                 <span
                                                                class="badge rounded-pill {{ $levelColors[$commission->level] ?? 'bg-secondary' }}">
                                                                Level {{ $commission->level }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            USDT {{ number_format($commission->deposit_amount, 2) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($commission->commission_percentage, 2) }}%
                                                        </td>
                                                        <td class="text-end fw-semibold text-success">
                                                            USDT {{ number_format($commission->commission_amount, 2) }}
                                                        </td>
                                                        <td>
                                                            {{ $commission->created_at?->format('M d, Y') }}<br>
                                                            <small class="text-muted">{{ $commission->created_at?->format('h:i A') }}</small>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No commission records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $commissions->appends(request()->query())->links() }}
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