@extends('layouts.user.app')

@section('title', 'Profile - ' . config('app.name'))

@section('content')
    <!-- Header Card -->
    <div class="bg-gradient-to-br from-rose-600 to-purple-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-start gap-4">
            @if(auth()->user()->avatar_path)
                <img src="{{ asset('uploads/avatar/' . auth()->user()->avatar_path) }}" alt="avatar"
                    class="w-16 h-16 rounded-full border-4 border-white/30 object-cover">
            @else
                <div class="w-16 h-16 rounded-full border-4 border-white/30 bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user text-3xl text-white/80"></i>
                </div>
            @endif
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
                    @php
                        $currentVipLevel = $vipLevel ?? 0;
                        $vipColors = [
                            1 => 'bg-gradient-to-r from-yellow-400 to-yellow-500',
                            2 => 'bg-gradient-to-r from-yellow-500 to-orange-500',
                            3 => 'bg-gradient-to-r from-orange-500 to-red-500',
                        ];
                        $vipIcons = [
                            1 => 'fa-star',
                            2 => 'fa-crown',
                            3 => 'fa-gem',
                        ];
                        $colorClass = $vipColors[$currentVipLevel] ?? 'bg-gray-400';
                        $iconClass = $vipIcons[$currentVipLevel] ?? 'fa-user';
                    @endphp
                    <span
                        class="{{ $colorClass }} text-white text-xs font-bold px-2.5 py-1 rounded inline-flex items-center gap-1">
                        <i class="fas {{ $iconClass }}"></i>
                        {{ $vipName ?? 'VIP 0' }}
                    </span>
                    @if(auth()->user()->username)
                        <span class="text-xs bg-white/20 px-2 py-1 rounded">{{ '@' . auth()->user()->username }}</span>
                    @endif
                </div>
                <div class="text-white/80 text-sm mt-1">Invitation Code: {{ auth()->user()->referral_code ?? '—' }}</div>
            </div>
            {{-- <a href="{{ route('notifications.index') }}" class="relative">
                <i class="fas fa-comment-dots text-white/90 text-xl"></i>
                @if(!empty($userNotificationUnread))
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                    {{ $userNotificationUnread }}
                </span>
                @endif
            </a> --}}

            {{-- <a href="{{ route('notifications.index') }}" class="relative mr-2">
                <div
                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition">
                    <i class="fas fa-bell text-gray-700"></i>
                </div>
                @if(!empty($userNotificationUnread) && $userNotificationUnread > 0)
                <span
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                    {{ $userNotificationUnread > 99 ? '99+' : $userNotificationUnread }}
                </span>
                @endif
            </a> --}}
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">
            <div>
                <div class="text-white/80 text-xs">Account Balance</div>
                <div class="flex items-end gap-1">
                    <span class="text-xs">USDT</span>
                    <span class="text-3xl font-bold">{{ number_format(auth()->user()->balance ?? 0, 2) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('deposit') }}" class="flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-wallet text-white"></i>
                    </div>
                    <span class="text-sm">Deposit</span>
                </a>
                <a href="{{ route('withdrawal') }}" class="flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                    <span class="text-sm">Withdraw</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5">
        <div class="grid grid-cols-4 gap-4 text-center">
            <a href="{{ route('teams') }}" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
                <span class="text-xs">Teams</span>
            </a>
            <a href="{{ route('records.index') }}" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="text-xs">Record</span>
            </a>
            <a href="{{ route('commissions.index') }}" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-coins"></i>
                </div>
                <span class="text-xs">Commission</span>
            </a>
            <a href="{{ route('invitation') }}" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-xs">Invite</span>
            </a>
        </div>
    </div>

    <!-- Menu List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <ul class="divide-y divide-gray-100">
            <li>
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-id-card text-gray-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Profile Setting</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('deposit.records') }}" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-gray-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Deposit Records</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('withdrawal.records') }}" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-gray-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Withdrawal Records</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('commissions.index') }}" class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Referral Commissions</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-power-off text-gray-600"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Log Out</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>
                </form>
            </li>
        </ul>
    </div>
@endsection