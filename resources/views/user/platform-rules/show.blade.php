@extends('layouts.user.app')

@section('title', $platformRule->name . ' - ' . config('app.name'))

@section('content')
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('platform-rules') }}"
            class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-gray-700"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Guideline Details</h1>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($platformRule->image)
            <img src="{{ asset($platformRule->image) }}" alt="{{ $platformRule->name }}" 
                class="w-full h-56 sm:h-80 object-cover">
        @else
            <div class="w-full h-56 sm:h-80 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <i class="fas fa-info-circle text-8xl text-indigo-300"></i>
            </div>
        @endif
        
        <div class="p-6">
            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">{{ $platformRule->name }}</h1>
            
            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                @if($platformRule->created_at)
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Published {{ $platformRule->created_at->format('M d, Y') }}</span>
                    </div>
                @endif
                @if($platformRule->updated_at && $platformRule->updated_at != $platformRule->created_at)
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-clock"></i>
                        <span>Updated {{ $platformRule->updated_at->format('M d, Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Description Content -->
            <div class="prose prose-sm sm:prose max-w-none text-gray-600">
                {!! $platformRule->description !!}
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('platform-rules') }}" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Guidelines</span>
        </a>
    </div>
@endsection
