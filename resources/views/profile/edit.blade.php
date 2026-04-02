@extends('layouts.user.app')

@section('title', __('ui.edit_profile') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header with Back Button -->
        <div class="mb-6 flex items-center gap-3">
            @include('components.back-button')
            <h1 class="text-2xl font-bold text-gray-800">{{ __('ui.profile_settings') }}</h1>
        </div>

        <!-- Header Card (Preview Info) -->
        <div
            class="bg-gradient-to-br from-rose-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white flex items-start gap-5">
            @if(auth()->user()->avatar_path)
                <img src="{{ asset('uploads/avatar/' . auth()->user()->avatar_path) }}" alt="avatar"
                    class="w-20 h-20 rounded-full border-4 border-white/30 object-cover">
            @else
                <div class="w-20 h-20 rounded-full border-4 border-white/30 bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user text-4xl text-white/80"></i>
                </div>
            @endif
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                    <span
                        class="bg-yellow-400 text-gray-900 text-xs font-bold px-2 py-1 rounded">{{ __('ui.level_1') }}</span>
                    @if(auth()->user()->username)
                        <span class="text-xs bg-white/20 px-2 py-1 rounded">{{ '@' . auth()->user()->username }}</span>
                    @endif
                </div>
                <div class="text-white/80 text-sm mt-2">{{ __('ui.invitation_code') }}:
                    {{ auth()->user()->referral_code ?? '—' }}</div>
                <div class="mt-3 flex gap-4 text-sm">
                    <div class="flex items-center gap-1"><i class="fas fa-phone"></i>
                        <span>{{ auth()->user()->phone }}</span>
                    </div>
                    @if(auth()->user()->email)
                        <div class="flex items-center gap-1"><i class="fas fa-envelope"></i>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Information Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ __('ui.basic_information') }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ __('ui.update_display_name_avatar') }}
            </p>
            @include('profile.partials.update-profile-information-form', ['user' => auth()->user()])
        </div>

        <!-- Password Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ __('ui.change_password') }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ __('ui.strong_password_hint') }}</p>
            @include('profile.partials.update-password-form')
        </div>

        <!-- Withdrawal Password Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ __('ui.change_withdrawal_password') }}</h2>
            <p class="text-sm text-gray-500 mb-6">{{ __('ui.update_withdrawal_password_hint') }}</p>
            @include('profile.partials.update-withdrawal-password-form')
        </div>

        <!-- Account Deletion Disabled Notice -->
        {{-- <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-1 flex items-center gap-2">
                <i class="fas fa-ban text-red-500"></i> Account Deletion Disabled
            </h2>
            <p class="text-sm text-gray-600">For security and compliance reasons, account deletion is not available. Contact
                support if you have special concerns.</p>
        </div> --}}
    </div>
@endsection