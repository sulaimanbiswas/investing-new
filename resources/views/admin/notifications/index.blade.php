@extends('admin.layouts.app')

@section('title', 'Notifications | Admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-dark fw-bold">All Notifications</h4>
                        <p class="text-muted mb-0">Manage and view system notifications</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if($unreadCount > 0)
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $unreadCount }} Unread</span>
                            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-check-double me-1"></i>Mark All Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        @if($notifications->count())
                            <div class="list-group list-group-flush">
                                @foreach($notifications as $notification)
                                    <div class="list-group-item {{ $notification->is_read ? 'bg-light' : 'bg-white' }} border-bottom">
                                        <div class="d-flex align-items-start gap-3">
                                            <!-- Icon -->
                                            <div class="flex-shrink-0">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle {{ $notification->is_read ? 'bg-secondary bg-opacity-25' : 'bg-primary' }}" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-{{ 
                                                        str_contains($notification->type, 'deposit') ? 'money-bill-wave' : 
                                                        (str_contains($notification->type, 'withdrawal') ? 'wallet' : 'bell') 
                                                    }} {{ $notification->is_read ? 'text-muted' : 'text-white' }} fa-lg"></i>
                                                </span>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-1 {{ $notification->is_read ? 'text-muted' : 'text-dark' }}" style="font-weight: 700;">
                                                            {{ $notification->title }}
                                                            @if(!$notification->is_read)
                                                                <span class="badge bg-danger rounded-circle ms-2" style="width: 10px; height: 10px; padding: 0;"></span>
                                                            @endif
                                                        </h6>
                                                        <p class="mb-2 {{ $notification->is_read ? 'text-muted' : 'text-secondary' }}">
                                                            {{ $notification->message }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex gap-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user me-1"></i>{{ $notification->user?->name ?? 'User' }}
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-tag me-1"></i>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                                        </small>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        @if(!$notification->is_read)
                                                            <form method="POST" action="{{ route('admin.notifications.read', $notification) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-check me-1"></i>Mark Read
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <a href="{{ route('admin.notifications.go', $notification) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-arrow-right me-1"></i>View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="p-3 border-top">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No notifications yet</h5>
                                <p class="text-muted">You'll see notifications here when there are updates.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
