@extends('layouts.user.app')

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Platform Menu</h1>
                <p class="text-gray-500 text-sm mt-0.5">Choose your VIP level and start earning</p>
            </div>
        </div>
    </div>

    <!-- Current Boost Badge -->
    <div class="mb-6">
        @if($hasActiveOrder)
            <div class="bg-gradient-to-br from-green-400 via-green-500 to-emerald-600 rounded-xl shadow-xl p-2">
                <div class="flex items-center gap-4 text-center">
                    
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium opacity-90 mb-1">Current Boost</p>
                        <p class="text-white text-xl font-bold">Order Available</p>
                    </div>
                                    </div>
            </div>
        @else
            <div class="bg-gradient-to-br from-slate-500 via-slate-600 to-gray-700 rounded-xl shadow-lg p-2">
                <div class="flex items-center gap-4">
                    
                    <div class="flex-1 text-center">
                        <p class="text-white text-sm font-medium opacity-90 mb-1">Current Boost</p>
                        <p class="text-white text-xl font-bold">No Order</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- VIP Filter Tabs -->
    <div class="grid grid-cols-4 gap-3 mb-6">
        <a href="{{ route('menu.index', ['vip_level' => 'all']) }}"
            class="px-4 py-3 text-center rounded-lg font-semibold transition-all shadow-sm {{ request('vip_level', 'all') === 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 hover:shadow-md' }}">
            <i class="fas fa-th-large text-sm mb-1"></i>
            <div class="text-xs">All</div>
        </a>
        <a href="{{ route('menu.index', ['vip_level' => 'vip1']) }}"
            class="px-4 py-3 text-center rounded-lg font-semibold transition-all shadow-sm {{ request('vip_level') === 'vip1' ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 hover:shadow-md' }}">
            <i class="fas fa-star text-sm mb-1"></i>
            <div class="text-xs">VIP 1</div>
        </a>
        <a href="{{ route('menu.index', ['vip_level' => 'vip2']) }}"
            class="px-4 py-3 text-center rounded-lg font-semibold transition-all shadow-sm {{ request('vip_level') === 'vip2' ? 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 hover:shadow-md' }}">
            <i class="fas fa-crown text-sm mb-1"></i>
            <div class="text-xs">VIP 2</div>
        </a>
        <a href="{{ route('menu.index', ['vip_level' => 'vip3']) }}"
            class="px-4 py-3 text-center rounded-lg font-semibold transition-all shadow-sm {{ request('vip_level') === 'vip3' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 hover:shadow-md' }}">
            <i class="fas fa-gem text-sm mb-1"></i>
            <div class="text-xs">VIP 3</div>
        </a>
    </div>

    <!-- Platforms List -->
    <div class="space-y-4">
        @forelse($platforms as $platform)
            @php
                // Determine VIP level based on package_name
                $vipBadge = '';
                $vipColor = '';
                $vipIcon = '';
                
                switch($platform->package_name) {
                    case 'vip1':
                        $vipBadge = 'VIP 1';
                        $vipColor = 'bg-gradient-to-r from-yellow-400 to-yellow-500';
                        $vipIcon = 'fa-star';
                        break;
                    case 'vip2':
                        $vipBadge = 'VIP 2';
                        $vipColor = 'bg-gradient-to-r from-yellow-500 to-orange-500';
                        $vipIcon = 'fa-crown';
                        break;
                    case 'vip3':
                        $vipBadge = 'VIP 3';
                        $vipColor = 'bg-gradient-to-r from-orange-500 to-red-500';
                        $vipIcon = 'fa-gem';
                        break;
                }
            @endphp

            <a href="{{ route('menu.platform.show', $platform) }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-4">
                    <div class="flex items-start gap-4">
                        <!-- Platform Logo -->
                        <div class="flex-shrink-0">
                            @if($platform->image)
                                <img src="{{ asset($platform->image) }}" alt="{{ $platform->name }}"
                                    class="w-16 h-16 rounded-lg object-cover shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="fas fa-store text-white text-2xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Platform Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $platform->name }}</h3>
                                <!-- VIP Badge -->
                                <div class="{{ $vipColor }} text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm whitespace-nowrap">
                                    <i class="fas {{ $vipIcon }} mr-1"></i>{{ $vipBadge }}
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-wallet text-indigo-600 w-4"></i>
                                    <span class="text-gray-600">Balance:</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ number_format($platform->start_price, 0) }} - {{ number_format($platform->end_price, 0) }} USDT
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="fas fa-percentage text-green-600 w-4"></i>
                                    <span class="text-gray-600">Commission:</span>
                                    <span class="font-bold text-green-600">{{ number_format($platform->commission, 1) }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Arrow Icon -->
                        <div class="flex-shrink-0 self-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-4 shadow-inner">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <p class="text-gray-600 text-lg font-semibold mb-1">No platforms available</p>
                <p class="text-gray-400 text-sm">Try selecting a different VIP level</p>
            </div>
        @endforelse
    </div>
@endsection