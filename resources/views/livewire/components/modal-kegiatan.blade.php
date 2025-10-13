<div>
  @if ($show)
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm flex justify-center items-center z-50 px-4">
      <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg animate-fade-in">

        <div class="flex justify-between items-center p-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-800">
            Detail Kegiatan
          </h2>
          <button wire:click="$set('show', false)"
                  class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1.5 transition-colors">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <div class="p-6 space-y-6">
          @if ($kegiatan)
            @if ($kegiatan->image)
              <div class="overflow-hidden rounded-xl shadow-sm border">
                <img src="{{ asset('storage/' . $kegiatan->image) }}"
                     alt="Gambar Kegiatan"
                     class="w-full h-56 object-cover transition-transform duration-300 hover:scale-105">
              </div>
            @endif

            <div class="space-y-5">

              <div class="flex items-start gap-x-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9l-5-5Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-500">Judul Kegiatan</p>
                  <p class="text-base font-semibold text-gray-900 break-words">
                    {{ $kegiatan->nama ?? '-' }}
                  </p>
                </div>
              </div>

              <div class="flex items-start gap-x-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-500">Dibuat pada tanggal</p>
                  <p class="text-base font-medium text-gray-800 break-words">
                    {{ $kegiatan->created_at->isoFormat('D MMMM YYYY') ?? '-' }}
                    <span class="text-gray-500 text-sm">({{ $kegiatan->created_at->diffForHumans() }})</span>
                  </p>
                </div>
              </div>

              <div class="flex items-start gap-x-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" x2="7" y1="12" y2="12"/><line x1="17" x2="7" y1="7" y2="7"/><line x1="17" x2="7" y1="17" y2="17"/></svg>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-500">Deskripsi</p>
                  <div class="max-h-60 overflow-y-auto mt-1">
                    <p class="text-base text-gray-700 leading-relaxed break-words text-justify pr-2">
                      {{ $kegiatan->deskripsi ?? '-' }}
                    </p>
                  </div>
                </div>
              </div>

            </div>
          @else
            <p class="text-gray-500 text-center py-8">Tidak ada data kegiatan untuk ditampilkan.</p>
          @endif
        </div>

        <div class="bg-gray-50 p-4 text-right rounded-b-2xl">
          <button wire:click="$set('show', false)"
                  class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300 transition-colors">
            Tutup
          </button>
        </div>
      </div>
    </div>
  @endif

  @style
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
      animation: fade-in 0.2s ease-out;
    }
  </style>
  @endstyle
</div>
