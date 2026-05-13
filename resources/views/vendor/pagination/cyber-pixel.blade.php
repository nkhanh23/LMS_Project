@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 bg-slate-800/50 border-2 border-black text-slate-600 cursor-not-allowed opacity-50">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 bg-cyber-surface border-2 border-black text-brand hover:bg-brand hover:text-black transition-colors pixel-shadow-sm active:translate-y-0.5">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-slate-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 bg-brand border-2 border-black text-black font-bold pixel-shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 bg-cyber-surface border-2 border-black text-slate-300 hover:border-brand hover:text-brand transition-colors pixel-shadow-sm active:translate-y-0.5">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 bg-cyber-surface border-2 border-black text-brand hover:bg-brand hover:text-black transition-colors pixel-shadow-sm active:translate-y-0.5">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-2 bg-slate-800/50 border-2 border-black text-slate-600 cursor-not-allowed opacity-50">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
