<div class="mx-auto mb-12 w-10/12" wire:poll.visible>
  <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Pengembalian Buku</h2>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
        @forelse ($datas as $data)
          <tr>
            <td class="px-6 py-4">{{ $data->book->title ?? '-' }}</td>
            <td class="px-6 py-4">{{ $data->loan_date?->format('d M Y') ?? '-' }}</td>
            <td class="px-6 py-4">{{ $data->return_date?->format('d M Y') ?? '-' }}</td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-teal-500 text-white font-bold">
                Sudah Dikembalikan
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <a href="{{ route('pengembalian.detail', ['id' => $data->id]) }}"
                 class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Detail
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengembalian.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
