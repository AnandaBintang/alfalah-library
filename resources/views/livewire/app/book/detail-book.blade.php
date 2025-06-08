<div class="mx-auto mb-19 w-10/12">
  <div class="flex flex-col md:flex-row gap-6">
    {{-- Cover Buku --}}
    <div class="flex-shrink-0 w-full md:w-1/3">
      <img
        src="{{ $data->cover_image_path ? asset('storage/' . $data->cover_image_path) : asset('images/default-book.png') }}"
        alt="{{ $data->title ?? 'No Title' }}"
        class="w-full h-auto object-cover rounded shadow"
      >
    </div>

    {{-- Detail Buku --}}
    <div class="flex-1 bg-white p-6 rounded shadow">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $data->title ?? '-' }}</h1>
      <h2 class="text-xl text-gray-600 mb-4">{{ $data->subtitle ?? '-' }}</h2>

      <div class="space-y-2 text-gray-700">
        <p><strong>ISBN:</strong> {{ $data->isbn ?? '-' }}</p>
        <p><strong>Stok:</strong> {{ $data->stock ?? '-' }}</p>
        <p><strong>Lokasi Rak:</strong> Rak {{ $data->rack_location ?? '-' }}</p>
        <p><strong>Publisher:</strong> {{ $data->publisher->name ?? '-' }}</p>

        <p>
          <strong>Kategori:</strong>
          @forelse ($data->categories as $category)
            <span
              class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-600 text-white">{{ $category->name }}</span>
          @empty
            <span class="text-gray-500">Tidak ada kategori</span>
          @endforelse
        </p>
      </div>

      {{-- Tombol Aksi --}}
      <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-2">
        <a href="{{ route('book.index') }}"
           class="inline-block w-full sm:w-auto font-medium text-center bg-gray-600 text-white py-3 px-5 rounded-lg hover:bg-gray-700 transition"
           wire:navigate>
          Kembali ke daftar buku
        </a>

        <button wire:click="addToCart({{ $data->id }})"
                type="button"
                class="w-full cursor-pointer sm:w-auto py-3 px-5 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
          Add to cart
          <svg class="shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="m5 11 4-7"></path>
            <path d="m19 11-4-7"></path>
            <path d="M2 11h20"></path>
            <path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8c.9 0 1.8-.7 2-1.6l1.7-7.4"></path>
            <path d="m9 11 1 9"></path>
            <path d="M4.5 15.5h15"></path>
            <path d="m15 11-1 9"></path>
          </svg>
        </button>
      </div>

    </div>
  </div>
</div>
