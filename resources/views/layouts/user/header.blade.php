<header
    class="bg-white shadow-sm fixed {{ session()->has('admin_logged_in_as_user') ? 'top-[64px]' : 'top-0' }} left-0 right-0 z-50 mb-3">
    <div class="px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-xl">$</span>
            </div>
            <span class="font-bold text-gray-800 text-lg hidden sm:block">{{ config('app.name', 'MALL') }}</span>
        </a>

        <div class="flex items-center gap-1">
            <!-- Notification Icon -->
            <a href="{{ route('notifications.index') }}" class="relative mr-2">
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
            </a>

            <!-- User Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-2 hover:bg-gray-50 px-3 py-2 rounded-lg transition ">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset('uploads/avatar/' . auth()->user()->avatar_path) }}" alt="avatar"
                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white border-2 border-gray-200">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="text-left hidden sm:block">
                        <div class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition x-cloak
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50"
                    style="display: none;">
                    <a href="{{ route('profile.home') }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                        <i class="fas fa-user-circle text-gray-400 w-5"></i>
                        <span class="text-gray-700">Profile</span>
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                        <i class="fas fa-cog text-gray-400 w-5"></i>
                        <span class="text-gray-700">Settings</span>
                    </a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition text-left">
                            <i class="fas fa-sign-out-alt text-red-500 w-5"></i>
                            <span class="text-red-600">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>