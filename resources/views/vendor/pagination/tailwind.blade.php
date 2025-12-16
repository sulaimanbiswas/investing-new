@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between px-4 py-6">
        <!-- Mobile View -->
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-lg hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 transition ease-in-out duration-150">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center ml-3 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-lg hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 transition ease-in-out duration-150">
                    Next
                    <i class="fas fa-chevron-right ml-2"></i>
                </a>
            @else
                <span
                    class="relative inline-flex items-center ml-3 px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                    Next
                    <i class="fas fa-chevron-right ml-2"></i>
                </span>
            @endif
        </div>

        <!-- Desktop View -->
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <!-- Pagination Info -->
            <div class="flex items-center text-sm text-gray-700">
                <span class="mr-4">
                    Showing
                    <span class="font-semibold text-gray-900">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-semibold text-gray-900">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
                    results
                </span>
            </div>

            <!-- Pagination Links -->
            <div class="flex items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-150">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span
                            class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    class="relative z-0 inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 border border-emerald-500 cursor-default leading-5 rounded-lg shadow-md">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-150">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-lg hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-150">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-lg">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif