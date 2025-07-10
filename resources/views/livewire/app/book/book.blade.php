<div class="mx-auto mb-19 w-10/12">
  {{-- Search & Filter --}}
  <form wire:submit.prevent="filterCategory"
        class="mb-6 flex flex-col md:flex-row md:items-end md:gap-6 gap-4">

    {{-- Search Label dan Input --}}
    <div class="flex flex-col w-full md:w-auto">
      <label for="searchInput" class="mb-1 font-medium text-gray-700">Cari Judul Buku</label>
      <input
        id="searchInput"
        type="text"
        wire:model.live="search"
        class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
        placeholder="Cari buku berdasarkan judul..."
      />
    </div>

    {{-- Category Label dan Dropdown --}}
    <div class="flex flex-col w-full md:w-auto">
      <label for="categorySelect" class="mb-1 font-medium text-gray-700">Filter Kategori</label>
      <select
        id="categorySelect"
        wire:model.defer="inputCategory"
        class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
      >
        <option value="">Semua Kategori</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>

    {{-- Submit Button --}}
    <div class="w-full md:w-auto">
      <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Cari
      </button>
    </div>
  </form>
  {{-- End Search & Filter --}}



  {{--  List Buku--}}
  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
    @foreach($datas as $data)

      @if($datas->count() == 0)
        <div class="col-span-6 text-center text-gray-500 whitespace-pre-line p-10">
          Tidak ada buku yang ditemukan.<br>
          Silakan coba kata kunci lain atau pilih kategori berbeda.
        </div>
      @endif
        <livewire:components.card-product
          :key="$data->id"
          :cardTitle="$data->title"
          :description="$data->subtitle"
          :image="$data->cover_image_path"
          :link="$data->id"
          wire:key="card-{{ $data->id }}"
        />
        @endforeach
  </div>

  {{--  List Buku--}}

  {{--  Pagination button--}}
  <div class="mt-4 flex justify-center">
    {{ $datas->links("vendor.livewire.custom-pagination") }}
  </div>
  {{--  Pagination button--}}

</div>
