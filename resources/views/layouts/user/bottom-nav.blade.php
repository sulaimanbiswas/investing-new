<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50">
    <div class="flex items-center justify-around px-4 py-3">
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }} transition">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs font-medium">Home</span>
        </a>
        <a href="{{ route('service.index') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('service.*') ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }} transition">
            <i class="fas fa-headset text-xl"></i>
            <span class="text-xs font-medium">Service</span>
        </a>
        <a href="{{ route('menu.index') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('menu.*') ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }} transition">
            <i class="fas fa-th-large text-xl"></i>
            <span class="text-xs font-medium">Menu</span>
        </a>
        <a href="{{ route('records.index') }}"
            class="flex flex-col items-center gap-1 text-gray-500 hover:text-yellow-500 transition">
            <i class="fas fa-file-alt text-xl"></i>
            <span class="text-xs font-medium">Record</span>
        </a>
        <a href="{{ route('profile.home') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('profile.home') ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }} transition">
            <i class="fas fa-user text-xl"></i>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</nav>