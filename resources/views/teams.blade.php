@extends('layouts.user.app')

@section('title', 'My Teams - ' . config('app.name'))

@section('content')
    <!-- Header Banner -->
    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Invite friends!</h1>
        <p class="text-purple-100 text-sm">Improve team collaboration to earn more</p>
        
        <!-- Quick Stats Card -->
        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 mt-4 flex items-center gap-3">
            <div class="w-12 h-12 bg-white/30 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div>
                <div class="font-bold text-lg">Refer and Earn Rewards</div>
                <div class="text-purple-100 text-sm">Share your referral link and start earning</div>
            </div>
        </div>
    </div>

    <!-- Level Tabs -->
    <div class="bg-white rounded-xl shadow-sm mb-4">
        <div class="flex border-b border-gray-200">
            <button onclick="switchLevel(1)" id="tab-1" 
                class="level-tab flex-1 px-4 py-3 text-center font-semibold transition border-b-2 border-purple-600 text-purple-600">
                Level 1
            </button>
            <button onclick="switchLevel(2)" id="tab-2" 
                class="level-tab flex-1 px-4 py-3 text-center font-semibold transition border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Level 2
            </button>
            <button onclick="switchLevel(3)" id="tab-3" 
                class="level-tab flex-1 px-4 py-3 text-center font-semibold transition border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                Level 3
            </button>
        </div>

        <!-- Level Content -->
        <div class="p-4">
            <!-- Level 1 Content -->
            <div id="level-1" class="level-content">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-800">Direct Referrals</h3>
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $level1Count }} Members
                        </span>
                    </div>
                </div>

                @if($level1->count() > 0)
                    <div class="space-y-3">
                        @foreach($level1 as $user)
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-600">@username_{{ $user->username }}</div>
                                            <div class="text-xs text-purple-600 mt-1">
                                                <i class="fas fa-users"></i> {{ $user->referrals->count() }} referrals
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Joined</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $user->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No direct referrals yet</p>
                        <p class="text-sm text-gray-400 mt-2">Share your referral link to build your team</p>
                    </div>
                @endif
            </div>

            <!-- Level 2 Content -->
            <div id="level-2" class="level-content hidden">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-800">Second Level Referrals</h3>
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $level2Count }} Members
                        </span>
                    </div>
                </div>

                @if($level2->count() > 0)
                    <div class="space-y-3">
                        @foreach($level2 as $user)
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-600">@username_{{ $user->username }}</div>
                                            <div class="text-xs text-blue-600 mt-1">
                                                <i class="fas fa-link"></i> Referred by {{ $user->referrer->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Joined</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $user->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-layer-group text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No second level referrals yet</p>
                        <p class="text-sm text-gray-400 mt-2">Your team members will bring more people</p>
                    </div>
                @endif
            </div>

            <!-- Level 3 Content -->
            <div id="level-3" class="level-content hidden">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-800">Third Level Referrals</h3>
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $level3Count }} Members
                        </span>
                    </div>
                </div>

                @if($level3->count() > 0)
                    <div class="space-y-3">
                        @foreach($level3 as $user)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-600">@username_{{ $user->username }}</div>
                                            <div class="text-xs text-green-600 mt-1">
                                                <i class="fas fa-sitemap"></i> Referred by {{ $user->referrer->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Joined</div>
                                        <div class="text-sm font-medium text-gray-700">{{ $user->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-network-wired text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">No third level referrals yet</p>
                        <p class="text-sm text-gray-400 mt-2">Your network will expand as your team grows</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Team Statistics -->
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $level1Count }}</div>
            <div class="text-xs text-gray-600 mt-1">Level 1</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $level2Count }}</div>
            <div class="text-xs text-gray-600 mt-1">Level 2</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $level3Count }}</div>
            <div class="text-xs text-gray-600 mt-1">Level 3</div>
        </div>
    </div>

    <!-- Total Team Size -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg p-4 text-white text-center">
        <div class="text-3xl font-bold">{{ $totalTeamSize }}</div>
        <div class="text-purple-100 text-sm mt-1">Total Team Members</div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchLevel(level) {
            // Hide all content
            document.querySelectorAll('.level-content').forEach(el => el.classList.add('hidden'));
            
            // Remove active state from all tabs
            document.querySelectorAll('.level-tab').forEach(tab => {
                tab.classList.remove('border-purple-600', 'text-purple-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected content
            document.getElementById('level-' + level).classList.remove('hidden');
            
            // Activate selected tab
            const activeTab = document.getElementById('tab-' + level);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-purple-600', 'text-purple-600');
        }
    </script>
@endpush
