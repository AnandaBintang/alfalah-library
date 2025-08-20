<div class="mx-auto mb-19 w-10/12">
  <div class="bg-white border border-gray-200 shadow-lg rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
      <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V6m0 12v-2"/>
      </svg>
      Form Pengajuan Donasi Buku
    </h2>

    <form wire:submit.prevent="submit" class="space-y-6">
      <!-- Nama Item -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Item
          <span class="text-red-500">*</span>
        </label>
        <input wire:model.defer="item_name" type="text"
               class="mt-1 block w-full p-3 rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
               placeholder="Contoh: Buku Fisika SMA"/>
        @error('item_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Deskripsi -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea wire:model.defer="description"
                  class="mt-1 block w-full p-3 rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                  rows="4"
                  placeholder="Deskripsikan isi atau kondisi item..."></textarea>
        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <!-- Jumlah dan Gambar -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Jumlah -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Jumlah
            <span class="text-red-500">*</span>
          </label>
          <input wire:model.defer="quantity" type="number" min="1"
                 class="mt-1 block w-full p-3 rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                 placeholder="1 atau lebih"/>
          @error('quantity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Upload Gambar -->
        <div
          x-data="{ uploading: false, progress: 0 }"
          x-on:livewire-upload-start="uploading = true"
          x-on:livewire-upload-finish="uploading = false"
          x-on:livewire-upload-cancel="uploading = false"
          x-on:livewire-upload-error="uploading = false"
          x-on:livewire-upload-progress="progress = $event.detail.progress">

          <label class="block text-sm font-medium text-gray-700">Gambar (opsional)</label>
          <input wire:model="image" type="file" accept="image/*"
                 class="mt-1 block w-full p-3 rounded-xl border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"/>
          <div x-show="uploading">
            <progress max="100" x-bind:value="progress"></progress>
          </div>
          @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          {{--          @if ($image)--}}
          {{--            <img src="{{ $image->temporaryUrl() }}">--}}
          {{--          @endif--}}
        </div>
      </div>

      <!-- Tombol Submit -->
      <div class="text-right">
        <button
          type="submit"
          class="w-full sm:w-auto inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

          <svg wire:loading.remove
               class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 13l4 4L19 7"/>
          </svg>

          <span wire:loading
                class="animate-spin inline-block size-4 border-3 border-current border-t-transparent rounded-full mr-1"></span>
          Ajukan Donasi
        </button>

      </div>
    </form>
  </div>
</div>
