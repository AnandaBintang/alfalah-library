<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="bg-white border border-gray-200 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Pengembalian</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
      <div>
        <span class="font-medium">Judul Buku:</span>
        <p>{{ $data->book->title ?? '-' }}</p>
      </div>
      <div>
        <span class="font-medium">Tanggal Pinjam:</span>
        <p>{{ $data->loan_date?->format('d M Y') ?? '-' }}</p>
      </div>
      <div>
        <span class="font-medium">Tanggal Jatuh Tempo:</span>
        <p>{{ $data->due_date?->format('d M Y') ?? '-' }}</p>
      </div>
      <div>
        <span class="font-medium">Tanggal Dikembalikan:</span>
        <p>{{ $data->return_date?->format('d M Y') ?? '-' }}</p>
      </div>
      <div class="md:col-span-2">
        <span class="font-medium">Status:</span>
        <p class="text-green-600 font-semibold">Sudah Dikembalikan</p>
      </div>
    </div>

    <div class="mt-6">
      <a href="{{ route('pengembalian.index') }}"
         class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium rounded-lg">
        ← Kembali ke Riwayat Pengembalian
      </a>
    </div>
  </div>
</div>
