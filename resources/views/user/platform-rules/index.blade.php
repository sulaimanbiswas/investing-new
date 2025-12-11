@extends('layouts.user.app')

@section('title', 'Platform Guidelines - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Platform Guidelines</h1>
    </div>

    <!-- Guidelines Grid -->
    <div class="grid grid-cols-1 gap-5">
        @forelse($platformRules as $rule)
            <a href="{{ route('platform-rules.show', $rule->id) }}"
                class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition block">
                @if($rule->image)
                    <img src="{{ asset($rule->image) }}" alt="{{ $rule->name }}" class="w-full h-48 sm:h-64 object-cover">
                @else
                    <div
                        class="w-full h-48 sm:h-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-6xl text-indigo-300"></i>
                    </div>
                @endif

                <div class="p-5">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">{{ $rule->name }}</h2>

                    <div class="prose prose-sm max-w-none text-gray-600 line-clamp-3">
                        {!! Str::limit(strip_tags($rule->description), 200) !!}
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        @if($rule->created_at)
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $rule->created_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                        <span class="text-sm text-indigo-600 font-medium">Read More <i
                                class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Guidelines Available</h3>
                <p class="text-gray-500">Platform guidelines will appear here once published.</p>
            </div>
        @endforelse
    </div>

    @if($platformRules->count() > 0)
        <!-- Footer Info -->
        <div class="mt-6 bg-indigo-50 rounded-xl p-4 text-center">
            <p class="text-sm text-indigo-700">
                <i class="fas fa-info-circle mr-1"></i>
                Please read and follow these guidelines for a better experience.
            </p>
        </div>
    @endif
@endsection