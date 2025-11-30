@extends('layouts.user.app')

@section('title', 'Profile - ' . config('app.name'))

@section('content')
    <!-- Header Card -->
    <div class="bg-gradient-to-br from-rose-600 to-purple-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-start gap-4">
            <img src="https://via.placeholder.com/64x64" alt="avatar"
                class="w-16 h-16 rounded-full border-4 border-white/30">
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
                    <span class="bg-yellow-400 text-gray-900 text-xs font-bold px-2 py-1 rounded">VIP 1</span>
                </div>
                <div class="text-white/80 text-sm">Invitation Code: {{ auth()->user()->referral_code ?? '—' }}</div>
            </div>
            <i class="fas fa-comment-dots text-white/80"></i>
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
                <a href="#" class="flex flex-col items-center justify-center">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-wallet text-white"></i>
                    </div>
                    <span class="text-sm">Deposit</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center">
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
            <a href="#" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="text-xs">Record</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-xs">Wallet Mgmt</span>
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
                <a href="#" class="flex items-center justify-between p-4">
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
                <a href="#" class="flex items-center justify-between p-4">
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
                <form method="POST" action="{{ route('logout') }}" class="flex items-center justify-between p-4">
                    @csrf
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-power-off text-gray-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Log Out</div>
                        </div>
                    </div>
                    <button type="submit"><i class="fas fa-chevron-right text-gray-400"></i></button>
                </form>
            </li>
        </ul>
    </div>
@endsection