<div class="mx-auto mb-19 w-10/12 border-gray-100 p-5 rounded-lg shadow-lg">
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

    {{-- Filter ebook --}}
    <div class="flex flex-col w-full md:w-auto">
      <label class="mb-1 font-medium text-gray-700">Tipe Buku</label>
      <div class="flex px-3 py-2 gap-4">
        <label class="inline-flex items-center">
          <input
            type="radio"
            name="bookType"
            wire:model="bookType"
            value="ebook"
            class="form-radio text-blue-600 focus:ring-blue-500 border-gray-300"
          >
          <span class="ml-2 text-gray-700">E-Book</span>
        </label>

        <label class="inline-flex items-center">
          <input
            type="radio"
            name="bookType"
            wire:model="bookType"
            value="pear"
            class="form-radio text-blue-600 focus:ring-blue-500 border-gray-300"
          >
          <span class="ml-2 text-gray-700">Fisik</span>
        </label>
      </div>
    </div>


    {{-- Submit Button --}}
    <div class="w-full md:w-auto">
      <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Cari
      </button>
    </div>
  </form>
  {{-- End Search & Filter --}}

  {{--  Top 3 books--}}
  @if ($topBooks->count())
    <div class="mb-6">
      <h3 class="text-xl font-semibold mb-4">📚 Top 3 Buku Terpopuler</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($topBooks as $book)
          <livewire:components.card-product
            :key="$book->id"
            :cardTitle="$book->title"
            :description="$book->subtitle"
            :image="$book->cover_image_path"
            :link="$book->id"
            wire:key="top-book-{{ $book->id }}"
          />
        @endforeach
      </div>
    </div>
  @endif
  {{--  Top 3 books--}}

  @if($topBooks->count())
    <hr class="border-t-2 w-full border-gray-300 my-6">
  @endif

  {{--  List Buku--}}
  <div class="mb-3">
    <h3 class="text-xl font-semibold mb-4">📚 List Buku</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
      @if($datas->count() == 0)
        <div class="col-span-6 text-center text-gray-500 whitespace-pre-line p-10">
          Tidak ada buku yang ditemukan.<br>
          Silakan coba kata kunci lain atau pilih kategori berbeda.
        </div>
      @endif

      @foreach($datas as $data)
        <livewire:components.card-product
          :cardTitle="$data->title"
          :description="$data->subtitle"
          :image="$data->cover_image_path"
          :link="$data->id"
          wire:key="card-{{ $data->id }}"
        />
      @endforeach
    </div>
  </div>

  {{--  List Buku--}}

  {{--  Pagination button--}}
  <div class="mt-7 flex justify-center">
    {{ $datas->links() }}
  </div>
  {{--  Pagination button--}}

</div>
