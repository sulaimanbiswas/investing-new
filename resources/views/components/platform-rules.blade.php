<!-- Platform Rules Section -->
<div class="space-y-4 mt-2">
    <h2 class="text-xl font-bold text-gray-800 px-2">Platform Guidelines</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @php
            $platformRules = \App\Models\PlatformRule::where('is_active', true)->orderBy('sort_by')->get();
        @endphp

        @forelse($platformRules as $rule)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                @if($rule->image)
                    <img src="{{ asset('storage/' . $rule->image) }}" alt="{{ $rule->name }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-5xl text-indigo-300"></i>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">{{ $rule->name }}</h3>
                    <p class="text-sm text-gray-600 line-clamp-3">{!! Str::limit(strip_tags($rule->description), 120) !!}
                    </p>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No platform rules available</p>
            </div>
        @endforelse
    </div>
</div>