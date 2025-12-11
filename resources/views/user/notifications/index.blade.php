@extends('layouts.user.app')

@section('title', 'Notifications - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
    </div>

    <div
        class="bg-gradient-to-r from-sky-500 to-blue-600 rounded-xl shadow-lg p-5 text-white mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-white/80">Stay updated</p>
            <h2 class="text-2xl font-bold">Notifications</h2>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <span class="px-3 py-2 bg-white/20 rounded-full text-sm text-center sm:text-left">Unread:
                {{ $unreadCount }}</span>
            <form method="POST" action="{{ route('notifications.read-all') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                    class="w-full sm:w-auto px-4 py-2 bg-white text-sky-600 font-semibold rounded-lg shadow hover:bg-gray-100 transition text-center">
                    Mark all read
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100">
        @forelse($notifications as $notification)
            <div class="p-4 flex items-start gap-3 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->is_read ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-600' }}">
                    <i class="fas {{ $notification->is_read ? 'fa-bell' : 'fa-bell' }}"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1">{{ $notification->message }}</p>
                    <div class="flex items-center gap-3 mt-3">
                        @if(!$notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="text-xs text-sky-600 font-semibold">Mark as read</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">Read</span>
                        @endif
                        <a href="{{ route('notifications.go', $notification) }}"
                            class="text-xs text-green-600 font-semibold">Open</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">
                No notifications yet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endsection