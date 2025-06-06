<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border rounded-xl shadow-md bg-white">
  {{-- Book Info --}}
  <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto">
    <img src="{{ asset('storage/' . $item->book->cover_image_path) }}" alt="{{ $item->book->title }}"
         class="w-16 h-20 object-cover rounded-lg shadow-sm">
    <div class="flex flex-col">
      <h3 class="font-semibold text-base sm:text-lg text-gray-800">{{ $item->book->title }}</h3>
      @if ($item->book->subtitle)
        <p class="text-sm text-gray-500">{{ $item->book->subtitle }}</p>
      @endif
    </div>
  </div>

  {{-- Quantity Controls --}}
  <div class="flex items-center justify-between sm:justify-center gap-3">
    <button wire:click="decrement"
            class="inline-flex items-center font-bold justify-center h-8 w-8 rounded-full border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
      –
    </button>
    <span class="text-base font-medium text-gray-700">{{ $item->quantity }}</span>
    <button wire:click="increment"
            class="inline-flex font-bold  items-center justify-center h-8 w-8 rounded-full border border-gray-300 bg-gray-100 hover:bg-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
      +
    </button>
  </div>

  {{-- Remove Button --}}
  <div class="flex justify-end sm:justify-start">
    <button wire:click="confirmRemove"
            class="text-sm text-red-600 hover:text-red-800 font-medium transition-all duration-150">
      Hapus
    </button>
  </div>
</div>
