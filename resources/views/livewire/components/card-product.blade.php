<div class="flex flex-col h-full bg-white border border-gray-200 shadow-2xs rounded-xl overflow-hidden cursor-pointer"
     wire:click="goToDetailBook({{ $link }})" wire:ignore>
  <img class="w-full h-48 object-cover mx-auto rounded-t-xl" src="{{ asset('storage/' . $image) }}" alt="Gambar Buku">
  <div class="p-4 md:p-5 flex flex-col flex-1">
    <h3 class="text-lg font-bold text-gray-800">
      {{ \Illuminate\Support\Str::limit($cardTitle, 20) ?? "-" }}
    </h3>
    <p class="mt-1 text-gray-500 line-clamp-3">
      {{ \Illuminate\Support\Str::limit($description, 100) ?? "-" }}
    </p>
    <div class="mt-auto pt-3">
      <a
        class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
        href="{{ route('book.detail', ['id' => $link]) }}"
      >
        Detail
      </a>
    </div>
  </div>
</div>
