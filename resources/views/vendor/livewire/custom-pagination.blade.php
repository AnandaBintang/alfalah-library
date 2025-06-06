<nav class="flex items-center -space-x-px" aria-label="Pagination">
  {{-- Previous Button --}}
  @if ($paginator->onFirstPage())
    <button disabled
            class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm first:rounded-s-lg last:rounded-e-lg border border-gray-200 text-gray-800 disabled:opacity-50 disabled:pointer-events-none"
            aria-label="Previous">
      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m15 18-6-6 6-6"></path>
      </svg>
      <span class="hidden sm:block">Previous</span>
    </button>
  @else
    <button wire:click="previousPage" type="button"
            class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm first:rounded-s-lg last:rounded-e-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
            aria-label="Previous">
      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
           stroke-linejoin="round">
        <path d="m15 18-6-6 6-6"></path>
      </svg>
      <span class="hidden sm:block">Previous</span>
    </button>
  @endif

  {{-- Pagination Elements --}}
  @foreach ($elements as $element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
      <span
        class="min-h-9.5 min-w-9.5 flex justify-center items-center border border-gray-200 text-gray-800 py-2 px-3 text-sm">{{ $element }}</span>
    @endif

    {{-- Array Of Links --}}
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <button wire:key="paginator-page-{{ $page }}" aria-current="page"
                  class="min-h-9.5 min-w-9.5 flex justify-center items-center bg-gray-200 text-gray-800 border border-gray-200 py-2 px-3 text-sm first:rounded-s-lg last:rounded-e-lg focus:outline-hidden focus:bg-gray-300">
            {{ $page }}
          </button>
        @else
          <button wire:key="paginator-page-{{ $page }}" type="button" wire:click="gotoPage({{ $page }})"
                  class="min-h-9.5 min-w-9.5 flex justify-center items-center border border-gray-200 text-gray-800 hover:bg-gray-100 py-2 px-3 text-sm first:rounded-s-lg last:rounded-e-lg focus:outline-hidden focus:bg-gray-100">
            {{ $page }}
          </button>
        @endif
      @endforeach
    @endif
  @endforeach

  {{-- Next Button --}}
  @if ($paginator->hasMorePages())
    <button wire:click="nextPage" type="button"
            class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm first:rounded-s-lg last:rounded-e-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
            aria-label="Next">
      <span class="hidden sm:block">Next</span>
      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
           stroke-linejoin="round">
        <path d="m9 18 6-6-6-6"></path>
      </svg>
    </button>
  @else
    <button disabled
            class="min-h-9.5 min-w-9.5 py-2 px-2.5 inline-flex justify-center items-center gap-x-1.5 text-sm first:rounded-s-lg last:rounded-e-lg border border-gray-200 text-gray-800 disabled:opacity-50 disabled:pointer-events-none"
            aria-label="Next">
      <span class="hidden sm:block">Next</span>
      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
           stroke-linejoin="round">
        <path d="m9 18 6-6-6-6"></path>
      </svg>
    </button>
  @endif
</nav>
