<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Peminjaman</h2>

    @if ($data)
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
        <div>
          <span class="font-medium">Judul Buku:</span>
          <p>{{ $data->book->title ?? '-' }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Pinjam:</span>
          <p>{{ \Carbon\Carbon::parse($data->loan_date)->format('d M Y') }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Jatuh Tempo:</span>
          <p>{{ \Carbon\Carbon::parse($data->due_date)->format('d M Y') }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Kembali:</span>
          <p>
            {{ $data->return_date ? \Carbon\Carbon::parse($data->return_date)->format('d M Y') : '-' }}
          </p>
        </div>

        <div>
          <span class="font-medium">Status:</span>
          <p>
            @if ($data->status === 'returned')
              <span class="text-green-600 font-semibold">Sudah Dikembalikan</span>
            @elseif ($data->status === 'borrowed')
              <span class="text-yellow-600 font-semibold">Dipinjam</span>
            @else
              <span class="text-gray-600 font-semibold">{{ ucfirst($data->status) }}</span>
            @endif
          </p>
        </div>

        @if ($data->description)
          <div class="md:col-span-2">
            <span class="font-medium">Keterangan:</span>
            <p>{{ $data->description }}</p>
          </div>
        @endif
      </div>
    @else
      <p class="text-center text-red-600 font-semibold">Data peminjaman tidak ditemukan.</p>
    @endif

    <div class="mt-6 flex items-center justify-between">
      <a href="{{ route('peminjaman.index') }}" class="inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
        ← Kembali ke Daftar Peminjaman
      </a>

      @if($data && $data->loan_status === \App\Enum\StatusLoanBookEnum::BORROWED->value)
        <button wire:click="requestExtension" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Request Perpanjangan 7 Hari
        </button>
      @endif
    </div>
  </div>
</div>
