@if ($paginator->hasPages())
<nav style="display: flex; justify-content: center; gap: 0.4rem; flex-wrap: wrap;">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="padding: 0.5rem 0.9rem; color: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.1); font-size: 0.8rem;">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding: 0.5rem 0.9rem; border: 1px solid rgba(0,0,0,0.15); text-decoration: none; color: var(--black); font-size: 0.8rem; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(0,0,0,0.15)'">‹</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding: 0.5rem 0.9rem; color: rgba(0,0,0,0.4); font-size: 0.8rem;">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="padding: 0.5rem 0.9rem; background: var(--gold); color: var(--black); font-weight: 700; font-size: 0.8rem;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 0.5rem 0.9rem; border: 1px solid rgba(0,0,0,0.15); text-decoration: none; color: var(--black); font-size: 0.8rem;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(0,0,0,0.15)'">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding: 0.5rem 0.9rem; border: 1px solid rgba(0,0,0,0.15); text-decoration: none; color: var(--black); font-size: 0.8rem;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(0,0,0,0.15)'">›</a>
    @else
        <span style="padding: 0.5rem 0.9rem; color: rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.1); font-size: 0.8rem;">›</span>
    @endif
</nav>
@endif
