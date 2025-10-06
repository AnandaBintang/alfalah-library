
<section class="py-12 sm:py-16 lg:py-20 bg-gray-50" wire:poll>
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div id="fitur" class="text-lg sm:text-xl text-blue-600 py-6 font-bold">Kegiatan {{ config('app.name') }}</div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 sm:gap-8">
      {{-- Card Kegiatan --}}
      @foreach($datas as $data)
        <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl">
          <img class="w-full h-auto rounded-t-xl"
               src="{{ asset('storage/' . $data->image) }}"
               alt="Card Image">
          <div class="p-4 md:p-5">
            <h3 class="text-lg font-bold text-gray-800">
              {{ $data->nama }}
            </h3>
            <p class="mt-1 text-gray-500">
              {{ \Illuminate\Support\Str::limit($data->deskripsi ?? '', 150) }}
            </p>
            <p class="mt-5 text-xs text-gray-500">
              Last updated {{ $data->created_at->diffForHumans() }}
            </p>
          </div>
        </div>
      @endforeach
    </div>
</section>

