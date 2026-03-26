@extends('layouts.auth')

@section('title', __('ui.email_verification'))

@section('content')
    <div class="text-center mb-8">
        <i class="fas fa-envelope-open-text text-8xl text-yellow-500"></i>
    </div>
    <h1 class="text-4xl font-bold text-center text-yellow-500 mb-6">{{ __('ui.verify_email') }}</h1>
    <p class="text-center text-gray-600 mb-8 text-sm leading-relaxed">
        {{ __('ui.verify_email_description') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 text-center">
            {{ __('ui.verification_link_sent') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
            class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:-translate-y-1 hover:shadow-xl mb-4">
            <i class="fas fa-paper-plane mr-2"></i> {{ __('ui.resend_verification_email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="w-full py-4 bg-transparent text-gray-600 border-2 border-gray-300 rounded-xl text-base font-semibold uppercase tracking-wide transition-all hover:border-indigo-500 hover:text-indigo-500">
            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('ui.logout') }}
        </button>
    </form>
@endsection