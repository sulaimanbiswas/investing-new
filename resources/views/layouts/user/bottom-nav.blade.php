<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50">
    <div class="flex items-center justify-around px-4 py-3">
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs font-medium">Home</span>
        </a>
        <a href="{{ route('deposit') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('deposit*') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition">
            <i class="fas fa-wallet text-xl"></i>
            <span class="text-xs font-medium">Recharge</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-gray-500 hover:text-indigo-600 transition">
            <i class="fas fa-money-bill-wave text-xl"></i>
            <span class="text-xs font-medium">Withdrawal</span>
        </a>
        <a href="{{ route('teams') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('teams') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition">
            <i class="fas fa-users text-xl"></i>
            <span class="text-xs font-medium">Teams</span>
        </a>
        <a href="{{ route('profile.home') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('profile.home') ? 'text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }} transition">
            <i class="fas fa-user text-xl"></i>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</nav>