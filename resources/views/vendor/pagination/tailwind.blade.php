@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="shop-pagination">
        @if ($paginator->onFirstPage())
            <span class="shop-pagination__link opacity-50" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m12.5 5-5 5 5 5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="shop-pagination__link" aria-label="{{ __('pagination.previous') }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m12.5 5-5 5 5 5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="shop-pagination__dots" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="shop-pagination__active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="shop-pagination__link" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="shop-pagination__link" aria-label="{{ __('pagination.next') }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m7.5 5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        @else
            <span class="shop-pagination__link opacity-50" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m7.5 5 5 5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        @endif
    </nav>
@endif
