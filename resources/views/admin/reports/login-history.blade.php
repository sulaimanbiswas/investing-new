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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Login History Report
                        @if($selectedUser)
                            <span class="badge badge-primary">{{ $selectedUser->username }}</span>
                        @endif
                    </h4>
                    <p class="text-muted mb-0">Total {{ $loginHistories->total() }} login records</p>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.reports.login-history') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success
                                    </option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">IP Address</label>
                                <input type="text" name="ip_address" class="form-control" placeholder="Search IP"
                                    value="{{ request('ip_address') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" placeholder="Search Country"
                                    value="{{ request('country') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Device</label>
                                <select name="device" class="form-control">
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
                            <div class="col-md-3">
                                <label class="form-label">From Date</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">To Date</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.reports.login-history') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo me-2"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

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
                                            <strong>{{ $history->user->username ?? 'N/A' }}</strong><br>
                                            <a href="{{ route('admin.reports.login-history', ['user_id' => $history->user_id] + request()->except('user_id')) }}"
                                                class="text-primary" style="text-decoration: none;">
                                                @{{ $history->user->username ?? 'N/A' }}
                                            </a>
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
                                            <span class="text-primary"
                                                style="font-weight: 500;">{{ $history->ip_address }}</span>
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
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted fa-3x mb-3"></i>
                                            <p class="text-muted mb-0">No login history found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($loginHistories->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $loginHistories->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details Modal -->
            <div class="modal fade" id="detailsModal{{ $history->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
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
                                            <td>{{ $history->user->username ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $history->user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $history->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>

                                    <h6 class="text-primary mb-3 mt-4"><i class="fas fa-network-wired me-2"></i>Network
                                        Information</h6>
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

                                    <h6 class="text-success mb-3 mt-4"><i class="fas fa-info-circle me-2"></i>Login Status
                                    </h6>
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
                            <a href="{{ route('admin.users.show', $history->user) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-user me-2"></i>View User
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection