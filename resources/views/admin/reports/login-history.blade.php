@extends('admin.layouts.app')

@section('title', 'Admin | Login History Report')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Reports</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Login History</a></li>
        </ol>
    </div>

    <div class="">
        <!-- Mobile: Accordion toggle button -->
        <div class="accordion accordion-header-bg accordion-bordered d-md-none mb-2" id="loginHistoryFilterAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header accordion-header-primary" id="loginHistoryFilterHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#loginHistoryFilterCollapse" aria-expanded="false"
                        aria-controls="loginHistoryFilterCollapse">
                        Filter
                    </button>
                </h2>
                <div id="loginHistoryFilterCollapse" class="accordion-collapse collapse"
                    aria-labelledby="loginHistoryFilterHeading" data-bs-parent="#loginHistoryFilterAccordion">
                    <div class="accordion-body">
                        <form method="GET" action="{{ route('admin.reports.login-history') }}" class="row g-2">
                            <div class="col-12">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm"
                                    placeholder="Search by username, email, IP, country...">
                            </div>

                            <div class="col-12">
                                <input type="text" name="daterange"
                                    class="form-control form-control-sm input-daterange-datepicker"
                                    value="@if(request('date_from') && request('date_to')){{ request('date_from') }} - {{ request('date_to') }}@endif"
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
                                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success
                                    </option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <select name="device" class="default-select form-control form-control-sm wide">
                                    <option value="">All Devices</option>
                                    <option value="Desktop" {{ request('device') === 'Desktop' ? 'selected' : '' }}>Desktop
                                    </option>
                                    <option value="Mobile" {{ request('device') === 'Mobile' ? 'selected' : '' }}>Mobile
                                    </option>
                                    <option value="Tablet" {{ request('device') === 'Tablet' ? 'selected' : '' }}>Tablet
                                    </option>
                                    <option value="Bot" {{ request('device') === 'Bot' ? 'selected' : '' }}>Bot</option>
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
        <form method="GET" action="{{ route('admin.reports.login-history') }}" class="row mb-3 g-2 d-none d-md-flex">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm"
                    placeholder="Search...">
            </div>

            <div class="col-md-2">
                <input type="text" name="daterange" class="form-control form-control-sm input-daterange-datepicker"
                    value="@if(request('date_from') && request('date_to')){{ request('date_from') }} - {{ request('date_to') }}@endif"
                    placeholder="Select Date Range">
            </div>

            <div class="col-md-2">
                <select name="user_id" class="default-select form-control form-control-sm wide">
                    <option value="">User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="default-select form-control form-control-sm wide">
                    <option value="">Status</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="device" class="default-select form-control form-control-sm wide">
                    <option value="">Device</option>
                    <option value="Desktop" {{ request('device') === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="Mobile" {{ request('device') === 'Mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="Tablet" {{ request('device') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="Bot" {{ request('device') === 'Bot' ? 'selected' : '' }}>Bot</option>
                </select>
            </div>

            <div class="col-md-1 d-grid">
                <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
            </div>
        </form>

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #ff6837; color: white;">
                            <tr>
                                <th>User</th>
                                <th>User Type</th>
                                <th>Login at</th>
                                <th>IP</th>
                                <th>Latitude<br>Location<br>Longitude</th>
                                <th>Browser | OS</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loginHistories as $history)
                                <tr>
                                    <td>
                                        @if($history->user)
                                            <strong>{{ $history->user->username }}</strong><br>
                                            <a href="{{ route('admin.reports.login-history', ['user_id' => $history->user_id] + request()->except('user_id')) }}"
                                                class="text-primary" style="text-decoration: none;">
                                                {{ "@" . $history->user->username }}
                                            </a>
                                        @else
                                            <strong class="text-muted">Deleted User</strong><br>
                                            <span class="text-muted">ID: {{ $history->user_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($history->user && $history->user->is_admin)
                                            <span class="badge badge-danger">admin</span>
                                        @else
                                            <span class="badge badge-info">user</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $history->created_at->format('Y-m-d h:i A') }}<br>
                                        <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="text-primary" style="font-weight: 500;">{{ $history->ip_address }}</span>
                                    </td>
                                    <td>
                                        @if($history->latitude)
                                            <strong>{{ $history->latitude }}</strong><br>
                                        @endif
                                        @if($history->city || $history->country)
                                            {{ $history->city }}@if($history->city && $history->country),
                                            @endif{{ $history->country }}<br>
                                        @endif
                                        @if($history->longitude)
                                            <strong>{{ $history->longitude }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $history->browser }}<br>
                                        {{ $history->platform }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailsModal{{ $history->id }}" style="border-radius: 4px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted fa-3x mb-3"></i>
                                        <p class="text-muted mb-0">No login history found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $loginHistories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Modals for each login history -->
    @foreach($loginHistories as $history)
        <div class="modal fade" id="detailsModal{{ $history->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Login Details - {{ $history->created_at->format('M d, Y h:i A') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>User Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Username:</th>
                                        <td>{{ $history->user ? $history->user->username : 'Deleted User' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $history->user ? $history->user->email : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $history->user ? $history->user->name : 'N/A' }}</td>
                                    </tr>
                                </table>

                                <h6 class="text-primary mb-3 mt-4"><i class="fas fa-network-wired me-2"></i>Network Information
                                </h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">IP Address:</th>
                                        <td><code>{{ $history->ip_address }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>ISP:</th>
                                        <td>{{ $history->isp ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Timezone:</th>
                                        <td>{{ $history->timezone ?? 'Unknown' }}</td>
                                    </tr>
                                </table>

                                <h6 class="text-primary mb-3 mt-4"><i class="fas fa-map-marked-alt me-2"></i>Location
                                    Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Country:</th>
                                        <td>{{ $history->country }} ({{ $history->country_code }})</td>
                                    </tr>
                                    <tr>
                                        <th>Region:</th>
                                        <td>{{ $history->region }} ({{ $history->region_code }})</td>
                                    </tr>
                                    <tr>
                                        <th>City:</th>
                                        <td>{{ $history->city ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Latitude:</th>
                                        <td>{{ $history->latitude ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Longitude:</th>
                                        <td>{{ $history->longitude ?? 'Unknown' }}</td>
                                    </tr>
                                </table>

                                @if($history->latitude && $history->longitude)
                                    <a href="https://www.google.com/maps?q={{ $history->latitude }},{{ $history->longitude }}"
                                        target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i>View on Google Maps
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-success mb-3"><i class="fas fa-laptop me-2"></i>Device Information</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Device Type:</th>
                                        <td>{{ $history->device }}</td>
                                    </tr>
                                    @if($history->device_model)
                                        <tr>
                                            <th>Device Model:</th>
                                            <td>{{ $history->device_model }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Browser:</th>
                                        <td>{{ $history->browser }} {{ $history->browser_version }}</td>
                                    </tr>
                                    <tr>
                                        <th>Platform:</th>
                                        <td>{{ $history->platform }} {{ $history->platform_version }}</td>
                                    </tr>
                                </table>

                                <h6 class="text-success mb-3 mt-4"><i class="fas fa-info-circle me-2"></i>Login Status</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Status:</th>
                                        <td>
                                            @if($history->status === 'success')
                                                <span class="badge badge-success">Success</span>
                                            @else
                                                <span class="badge badge-danger">Failed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($history->failure_reason)
                                        <tr>
                                            <th>Failure Reason:</th>
                                            <td class="text-danger">{{ $history->failure_reason }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Login Time:</th>
                                        <td>{{ $history->created_at->format('F d, Y h:i:s A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Ago:</th>
                                        <td>{{ $history->created_at->diffForHumans() }}</td>
                                    </tr>
                                </table>

                                <h6 class="text-muted mb-2 mt-4">User Agent:</h6>
                                <small class="text-muted font-monospace">{{ $history->user_agent }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        @if($history->user)
                            <a href="{{ route('admin.users.show', $history->user) }}" class="btn btn-primary">
                                <i class="fas fa-user me-2"></i>View User
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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

                // Create hidden inputs for date_from and date_to
                var form = $(this).closest('form');
                form.find('input[name="date_from"]').remove();
                form.find('input[name="date_to"]').remove();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'date_from',
                    value: picker.startDate.format('YYYY-MM-DD')
                }).appendTo(form);

                $('<input>').attr({
                    type: 'hidden',
                    name: 'date_to',
                    value: picker.endDate.format('YYYY-MM-DD')
                }).appendTo(form);
            });

            $('.input-daterange-datepicker').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                var form = $(this).closest('form');
                form.find('input[name="date_from"]').remove();
                form.find('input[name="date_to"]').remove();
            });
        });
    </script>
@endpush