<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Perpanjangan</h2>

    @if ($data)
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
        <div>
          <span class="font-medium">Judul Buku:</span>
          <p>{{ $data->loan->book->title ?? '-' }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Pengajuan:</span>
          <p>{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Jatuh Tempo Lama:</span>
          <p>{{ \Carbon\Carbon::parse($data->old_due_date)->format('d M Y') }}</p>
        </div>

        <div>
          <span class="font-medium">Tanggal Jatuh Tempo Baru:</span>
          <p>{{ \Carbon\Carbon::parse($data->new_due_date)->format('d M Y') }}</p>
        </div>

        @php
          $status = $data->status;

          if ($status === 'approved') {
              $statusConfig = ['color' => 'bg-teal-500 text-white', 'label' => 'approved'];
          } else {
              $statusConfig = match ($status) {
                  'pending'  => ['color' => 'bg-yellow-600 text-white', 'label' => 'pending'],
                  'rejected' => ['color' => 'bg-red-600 text-white', 'label' => 'rejected'],
                  default    => ['color' => 'bg-gray-600 text-white', 'label' => $status],
              };
          }
        @endphp

        <div>
          <span class="font-medium block mb-1">Status:</span>
          <p>
            <span class="font-semibold px-2 py-1 rounded-lg {{ $statusConfig['color'] }}">
              {{ $statusConfig['label'] }}
            </span>
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
      <p class="text-center text-red-600 font-semibold">Data perpanjangan tidak ditemukan.</p>
    @endif

    <div class="mt-6">
      <a href="{{ route('perpanjang-peminjaman.index') }}" class="inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
        ← Kembali ke Riwayat Perpanjangan
      </a>
    </div>
  </div>
</div>
