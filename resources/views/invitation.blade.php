@extends('layouts.user.app')

@section('title', 'Invitation Program - ' . config('app.name'))

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <div
                class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Invitation Program</h1>
                <p class="text-gray-600 text-sm">Share and earn rewards together!</p>
            </div>
        </div>
    </div>

    <!-- Referral System Section -->
    @include('components.referral-section')

    <!-- How It Works Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mt-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-indigo-600"></i>
            How It Works
        </h3>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">1</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Share Your Code</h4>
                    <p class="text-gray-600 text-sm">Copy your unique referral code or link and share it with friends.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">2</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Friends Sign Up</h4>
                    <p class="text-gray-600 text-sm">When they register using your code, they become part of your team.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-indigo-600 font-bold">3</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Earn Rewards</h4>
                    <p class="text-gray-600 text-sm">Get rewarded for every successful referral and their activities.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div
        class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-sm p-4 sm:p-6 mt-4 border border-indigo-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-gift text-purple-600"></i>
            Referral Benefits
        </h3>
        <div class="grid sm:grid-cols-2 gap-3">
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">Commission Earnings</h4>
                </div>
                <p class="text-gray-600 text-sm">Earn commission from your referrals' activities.</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">Team Building</h4>
                </div>
                <p class="text-gray-600 text-sm">Build your own team and grow together.</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">Unlimited Referrals</h4>
                </div>
                <p class="text-gray-600 text-sm">No limit on how many people you can refer.</p>
            </div>
            <div class="bg-white/70 backdrop-blur rounded-lg p-4">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <h4 class="font-semibold text-gray-800">Passive Income</h4>
                </div>
                <p class="text-gray-600 text-sm">Earn passive income from your network.</p>
            </div>
        </div>
    </div>
@endsection