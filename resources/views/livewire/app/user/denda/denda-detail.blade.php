<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="bg-white shadow border border-gray-200 rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Detail Denda</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
      <div>
        <span class="font-medium">Judul Buku:</span>
        {{-- Gunakan ?-> untuk keamanan berantai --}}
        <p> {{ $fines->loan?->book?->title ?? $fines->book?->title ?? '-' }}</p>
      </div>

      <div>
        <span class="font-medium">Tanggal Pinjam:</span>
        {{-- Tambahkan ?-> setelah loan --}}
        <p>{{ $fines->loan?->loan_date ? \Carbon\Carbon::parse($fines->loan->loan_date)->format('d M Y') : '-' }}</p>
      </div>

      <div>
        <span class="font-medium">Tanggal Jatuh Tempo:</span>
        <p>{{ $fines->loan?->due_date ? \Carbon\Carbon::parse($fines->loan->due_date)->format('d M Y') : '-' }}</p>
      </div>

      <div>
        <span class="font-medium">Tanggal Kembali:</span>
        <p>{{ $fines->loan?->return_date ? \Carbon\Carbon::parse($fines->loan->return_date)->format('d M Y') : '-' }}</p>
      </div>

      <div>
        <span class="font-medium">Jumlah Denda:</span>
        <p class="text-red-600 font-semibold">Rp{{ number_format($fines->amount, 0, ',', '.') }}</p>
      </div>

      <div>
        <span class="font-medium">Status:</span>
        <p>
          @if ($fines->status === 'unpaid')
            <span
              class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">
              Belum Dibayar
            </span>
          @else
            <span
              class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-500 text-white">
              Sudah Dibayar
            </span>
          @endif
        </p>
      </div>

      @if ($fines->paid_date)
        <div>
          <span class="font-medium">Tanggal Dibayar:</span>
          <p>{{ \Carbon\Carbon::parse($fines->paid_date)->format('d M Y') }}</p>
        </div>
      @endif

      @if ($fines->description)
        <div class="md:col-span-2">
          <span class="font-medium">Keterangan:</span>
          <p>{{ $fines->description }}</p>
        </div>
      @endif
    </div>

    <div class="mt-6">
      <a href="{{ route('denda.index') }}" wire:navigate
         class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-gray-800 text-white text-sm font-medium rounded-lg">
        Kembali ke Daftar Denda
      </a>
    </div>
  </div>
</div>
