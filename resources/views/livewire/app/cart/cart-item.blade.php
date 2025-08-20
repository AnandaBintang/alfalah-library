<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border border-gray-100 rounded-xl shadow-md bg-white">
  {{-- Book Info --}}
  <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto">
    <img src="{{ asset('storage/' . $item->book->cover_image_path) }}" alt="{{ $item->book->title }}"
         class="w-16 h-20 object-cover rounded-lg shadow-sm">
    <div class="flex flex-col">
      <h3 class="font-semibold text-base sm:text-lg text-gray-800">
        {{ \Illuminate\Support\Str::limit($item->book->title, 13) }}
      </h3>
      @if ($item->book->subtitle)
        <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($item->book->subtitle, 29) }}</p>
      @endif
      <span class="text-sm text-gray-600 mt-1">Jumlah: {{ $item->quantity }}</span>
    </div>
  </div>

  {{-- Remove Button --}}
  <div class="flex justify-end sm:justify-start">
    <button wire:click="confirmRemove"
            class="text-sm text-red-600 hover:text-red-800 font-medium transition-all duration-150">
      Hapus
    </button>
  </div>
</div>
