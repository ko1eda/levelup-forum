@if ($paginator->hasPages())
<nav class="pagination is-small" role="navigation" aria-label="pagination">
    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li>
              <a class="pagination-link" aria-label="@lang('pagination.previous')" disabled aria-disabled="true">
                &lsaquo;
              </a>
            </li>
        @else
            <li>
              <a class="pagination-link" rel="prev" aria-label="@lang('pagination.previous')" href="{{ $paginator->previousPageUrl() }}">
                &lsaquo;
              </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                {{-- <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li> --}}
                <li><span class="pagination-ellipsis">&hellip;</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><a class="pagination-link is-current" aria-current="page">{{ $page }}</a></li>
                    @else
                        <li><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
              <a class="pagination-link" rel="next" aria-label="@lang('pagination.next')" href="{{ $paginator->nextPageUrl() }}">
                &rsaquo;
              </a>
            </li>
        @else
            <li>
              <a class="pagination-link" aria-label="@lang('pagination.next')" aria-disabled="true" disabled>
                &rsaquo;
              </a>
            </li>
        @endif
    </ul>
  </nav>
@endif
