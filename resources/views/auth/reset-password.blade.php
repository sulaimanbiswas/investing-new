@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-10">Reset Password</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="relative mb-6">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="text" name="identifier" placeholder="Phone / Username / Email"
                value="{{ old('identifier', $request->identifier ?? $request->email) }}"
                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required autofocus>
        </div>

        <div class="relative mb-6">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" name="password" placeholder="New Password"
                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
        </div>

        <div class="relative mb-6">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required>
        </div>

        <button type="submit"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl">Reset
            Password</button>
    </form>
@endsection