@extends('layouts.auth')

@section('title', __('ui.confirm_password'))

@section('content')
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-6">{{ __('ui.confirm_password') }}</h1>
    <p class="text-center text-gray-600 mb-8 text-sm leading-relaxed">
        {{ __('ui.confirm_password_description') }}
    </p>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="relative mb-6">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 text-lg"></i>
            <input type="password" name="password" placeholder="{{ __('ui.password') }}"
                class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-base transition-all focus:outline-none focus:border-indigo-500 focus:bg-white bg-indigo-50"
                required autofocus>
        </div>

        <button type="submit"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl">{{ __('ui.confirm') }}</button>
    </form>
@endsection