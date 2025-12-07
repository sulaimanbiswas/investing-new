@if ($paginator->hasPages())
    <nav class="d-flex justify-content-between align-items-center mt-4 pt-2" role="navigation"
        aria-label="Pagination Navigation">
        <!-- Mobile view: Simple Previous/Next -->
        <div class="d-md-none flex-grow-1">
            <div class="d-flex gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="btn btn-sm btn-light disabled" aria-disabled="true">
                        <i class="la la-angle-left"></i> Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-light" rel="prev">
                        <i class="la la-angle-left"></i> Previous
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-light ms-auto" rel="next">
                        Next <i class="la la-angle-right"></i>
                    </a>
                @else
                    <span class="btn btn-sm btn-light disabled ms-auto" aria-disabled="true">
                        Next <i class="la la-angle-right"></i>
                    </span>
                @endif
            </div>
        </div>

        <!-- Desktop view: Full pagination -->
        <div class="d-none d-md-flex">
            <ul class="pagination pagination-gutter pagination-primary pagination-sm no-bg">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item page-indicator disabled">
                        <a class="page-link" href="javascript:void(0)" aria-disabled="true">
                            <i class="la la-angle-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item page-indicator">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="la la-angle-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item page-indicator">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="la la-angle-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item page-indicator disabled">
                        <a class="page-link" href="javascript:void(0)" aria-disabled="true">
                            <i class="la la-angle-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Page info: shown on both mobile and desktop -->
        <div class="text-muted small ms-3 d-none d-md-block">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }}
            of {{ $paginator->total() ?? 0 }} results
        </div>
    </nav>
@endif