@extends('layouts.user.app')

@section('title', 'Wallet Management - ' . config('app.name'))

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center gap-3">
        @include('components.back-button')
        <h1 class="text-2xl font-bold text-gray-800">Wallet Management</h1>
    </div>

    <div class="bg-gradient-to-br from-rose-600 to-purple-700 rounded-xl shadow-lg p-5 text-white mb-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-wallet text-white text-xl"></i>
            </div>
            <div>
                <div class="text-sm text-white/80">Manage your withdrawal wallet</div>
                <h2 class="text-2xl font-bold">Wallet Management</h2>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Default Wallet Address</h3>
        <p class="text-sm text-gray-500 mb-4">This address will be used for withdrawals.</p>

        <form action="{{ route('wallet.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="withdrawal_address" class="block text-sm font-medium text-gray-700 mb-1">Wallet Address</label>
                <input type="text" id="withdrawal_address" name="withdrawal_address"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                    value="{{ old('withdrawal_address', $user->withdrawal_address) }}" required>
                @error('withdrawal_address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('profile.home') }}"
                    class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow">
                    Save Address
                </button>
            </div>
        </form>
    </div>
@endsection