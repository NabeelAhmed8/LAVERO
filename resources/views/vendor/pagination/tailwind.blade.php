@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // Industry Standard Sliding-Window Pagination Algorithm
        $items = [];
        if ($last <= 7) {
            for ($i = 1; $i <= $last; $i++) {
                $items[] = $i;
            }
        } else {
            if ($current <= 4) {
                $items = [1, 2, 3, 4, 5, '...', $last];
            } elseif ($current >= $last - 3) {
                $items = [1, '...', $last - 4, $last - 3, $last - 2, $last - 1, $last];
            } else {
                $items = [1, '...', $current - 1, $current, $current + 1, '...', $last];
            }
        }
    @endphp
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
        {{-- Results Counter --}}
        <div class="text-center sm:text-left order-2 sm:order-1">
            <p class="text-xs text-neutral-400 font-mono">
                Showing
                <span class="text-white font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="text-white font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="text-[#c5a880] font-medium">{{ $paginator->total() }}</span>
                creations
            </p>
        </div>

        {{-- Industry Standard Numbered Pagination Controls --}}
        <div class="flex items-center justify-center order-1 sm:order-2">
            <span class="relative z-0 inline-flex items-center gap-1 p-1 rounded-xl bg-neutral-950/90 border border-white/10 text-xs shadow-inner">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 text-xs font-medium text-neutral-600 rounded-lg cursor-not-allowed select-none" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline text-[11px] uppercase tracking-wider">Prev</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-[#c5a880] hover:bg-neutral-900 rounded-lg transition-colors" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline text-[11px] uppercase tracking-wider">Prev</span>
                    </a>
                @endif

                {{-- Page Numbers & Ellipses --}}
                @foreach ($items as $item)
                    @if ($item === '...')
                        <span class="inline-flex items-center justify-center min-w-[28px] h-8 text-xs font-mono text-neutral-500 select-none">
                            ...
                        </span>
                    @elseif ($item == $current)
                        <span aria-current="page">
                            <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2.5 text-xs font-mono font-semibold text-black bg-[#c5a880] rounded-lg shadow-sm">
                                {{ $item }}
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->url($item) }}" class="inline-flex items-center justify-center min-w-[32px] h-8 px-2.5 text-xs font-mono text-neutral-400 hover:text-white hover:bg-neutral-900 rounded-lg transition-colors" aria-label="{{ __('Go to page :page', ['page' => $item]) }}">
                            {{ $item }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 text-xs font-medium text-neutral-300 hover:text-[#c5a880] hover:bg-neutral-900 rounded-lg transition-colors" aria-label="{{ __('pagination.next') }}">
                        <span class="hidden sm:inline text-[11px] uppercase tracking-wider">Next</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 text-xs font-medium text-neutral-600 rounded-lg cursor-not-allowed select-none" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span class="hidden sm:inline text-[11px] uppercase tracking-wider">Next</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </span>
        </div>
    </nav>
@endif
