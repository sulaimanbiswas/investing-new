@extends('admin.layouts.app')

@section('title', 'Admin | Admin Users')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Admin Users</a></li>
        </ol>
    </div>

    @if(session('success'))
        <div class="alert alert-success solid alert-dismissible fade show">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger solid alert-dismissible fade show">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Admin Users</h4>
        </div>
        <div class="card-body">
            <form method="GET" class="row mb-3 g-2">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search by username, phone or email">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-secondary" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive recentOrderTable">
                <table class="table verticle-middle table-responsive-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Join Date</th>
                            <th class="text-end" style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td><span class="text-primary">@</span>{{ $user->username }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email ?: 'N/A' }}</td>
                                <td>{{ $user->created_at->format('F j, Y') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-primary btn-sm text-primary "
                                        href="{{ route('admin.users.show', $user) }}">
                                        <i class="fas fa-eye me-1 "></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No admin users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
@endsection