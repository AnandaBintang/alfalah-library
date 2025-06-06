<div class="max-w-3xl mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm mt-8">
  <h1 class="text-2xl font-semibold text-gray-800 mb-6">Detail Donasi</h1>

  @if($datas)
    <div class="space-y-4 text-gray-700">
      <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-300 pb-3">
        <span class="font-medium text-gray-900">Nama Donatur:</span>
        <span>{{ auth()->user()->name }}</span>
      </div>

      <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-300 pb-3">
        <span class="font-medium text-gray-900">Judul Barang:</span>
        <span>{{ $datas->item_name ?? '-' }}</span>
      </div>

      <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-300 pb-3">
        <span class="font-medium text-gray-900">Jumlah:</span>
        <span>{{ $datas->quantity ?? '-' }}</span>
      </div>

      <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-300 pb-3">
        <span class="font-medium text-gray-900">Status Donasi:</span>
        <span>
          @if($datas->status === \App\Enum\ApprovalStatusEnum::PENDING->value)
            <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-blue-500 text-white">Pending</span>
          @elseif($datas->status === \App\Enum\ApprovalStatusEnum::APPROVED->value)
            <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-green-600 text-white">Disetujui</span>
          @elseif($datas->status === \App\Enum\ApprovalStatusEnum::REJECTED->value)
            <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-red-600 text-white">Ditolak</span>
          @else
            <span>-</span>
          @endif
        </span>
      </div>

      <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-300 pb-3">
        <span class="font-medium text-gray-900">Tanggal Pengajuan:</span>
        <span>{{ \Carbon\Carbon::parse($datas->donation_date)->format('d M Y') ?? '-' }}</span>
      </div>

      <div class="mt-6 text-center">
        <img src="{{ asset('storage/' . $datas->image) }}" alt="Donasi Image"
             class="mx-auto max-h-64 rounded border border-gray-300 shadow-sm"/>
      </div>
    </div>
  @else
    <p class="text-center text-gray-500">Data donasi tidak ditemukan.</p>
  @endif

  <div class="mt-8">
    <a href="{{ route('donasi.index') }}"
       class="inline-block px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition">
      ← Kembali ke Daftar Donasi
    </a>
  </div>
</div>
