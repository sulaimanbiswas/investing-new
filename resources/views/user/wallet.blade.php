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
                <h2 class="text-2xl font-bold">Wallet Management</h2>
                <div class="text-sm text-white/80">
                    You can't change after setup your wallet.
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <form action="{{ route('wallet.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="withdrawal_name" class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Name</label>
                <input type="text" id="withdrawal_name" name="withdrawal_name"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->withdrawal_name) bg-gray-50 cursor-not-allowed @endif"
                    value="{{ old('withdrawal_name', $user->withdrawal_name) }}" @if($user->withdrawal_name)
                    disabled @else required @endif
                    placeholder="@if($user->withdrawal_name) Name already set @else Enter your withdrawal name @endif">
                @error('withdrawal_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="wallet_gateway" class="block text-sm font-medium text-gray-700 mb-1">Wallet Currency Protocol</label>
                <select id="wallet_gateway" name="wallet_gateway"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->wallet_gateway) bg-gray-50 cursor-not-allowed @endif"
                    @if($user->wallet_gateway) disabled @else required @endif>
                    <option value="" disabled selected>Select Protocol</option>
                    @foreach ($gateways as $gateway)
                        <option value="{{ $gateway->name }}" @if(old('wallet_gateway',
                            $user->wallet_gateway)==$gateway->name) selected @endif>
                            {{ $gateway->name }}
                        </option>
                                               
                    @endforeach
                </select>
                @if($user->wallet_gateway)
                    <input type="hidden" name="wallet_gateway" value="{{ $user->wallet_gateway }}">
                @endif
            </div>

            <div>
                <label for="withdrawal_address" class="block text-sm font-medium text-gray-700 mb-1">Wallet Address (USDT
                    TRC20)
                    @if(!$user->withdrawal_address) <span class="text-red-500">*</span>@endif
                </label>
                <input type="text" id="withdrawal_address" name="withdrawal_address"
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500 @if($user->withdrawal_address) bg-gray-50 cursor-not-allowed @endif"
                    value="{{ old('withdrawal_address', $user->withdrawal_address) }}" @if($user->withdrawal_address)
                    disabled @else required @endif
                    placeholder="@if($user->withdrawal_address) Address already set @else Enter your wallet address @endif">
                @if($user->withdrawal_address)
                    <input type="hidden" name="withdrawal_address" value="{{ $user->withdrawal_address }}">
                @endif
                @error('withdrawal_address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- wallet password --}}
            <div>
                <label for="wallet_password" class="block text-sm font-medium text-gray-700 mb-1">Wallet Password <span class="text-red-500">*</span></label>
                <input type="password" id="wallet_password" name="wallet_password" required
                    class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Enter your wallet password">
                @error('wallet_password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" @if($user->withdrawal_address) disabled
                class="px-4 py-2 rounded-lg bg-gray-400 text-white cursor-not-allowed" @else
                    class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow" @endif>
                    Save Address
                </button>
            </div>
        </form>
    </div>
@endsection