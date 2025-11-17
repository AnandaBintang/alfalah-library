<section class="py-12 sm:py-16 lg:py-20 bg-gray-50" wire:poll>
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div id="fitur" class="text-lg sm:text-xl text-blue-600 py-6 font-bold">Kegiatan {{ config('app.name') }}</div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 sm:gap-8">
      {{-- Card Kegiatan --}}
      {{-- Card Kegiatan --}}
      @foreach($datas as $data)
        <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">

          {{-- Gambar dengan Aspek Rasio Tetap --}}
          <div class="aspect-video overflow-hidden rounded-t-xl">
            <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 src="{{ asset('storage/' . $data->image) }}"
                 alt="Gambar untuk {{ $data->nama }}">
          </div>

          {{-- Konten Utama (dibuat 'flex-grow' agar mendorong footer ke bawah) --}}
          <div class="p-4 md:p-5 flex-grow">
            <h3 class="text-lg font-bold text-gray-800">
              {{ $data->nama }}
            </h3>
            <p class="mt-2 text-sm text-gray-600">
              {{-- Memberi sedikit lebih banyak ruang untuk deskripsi --}}
              {{ \Illuminate\Support\Str::limit($data->deskripsi ?? '', 65) }}
            </p>
          </div>

          {{-- Footer Card (selalu di bagian bawah) --}}
          <div class="p-4 md:p-5 border-t border-gray-200 flex justify-between items-center">
            {{-- Informasi Waktu dengan Ikon --}}
            <p class="text-xs text-gray-500 flex items-center gap-x-1.5">
              <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
              {{ $data->created_at->diffForHumans() }}
            </p>

            {{-- Tombol Detail --}}
            <button
              type="button"
              class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
              wire:click="$dispatch('open-modal', { id: {{ $data->id }} })">
              Lihat Detail
            </button>
          </div>
        </div>
      @endforeach
    </div>
    <livewire:components.modal-kegiatan/>
</section>

