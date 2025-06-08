<div class="mx-auto mb-19 w-10/12" wire:poll.visible>
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Riwayat Perpanjangan Peminjaman</h2>

    <div class="flex flex-col">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
              <tr>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Permintaan</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Jatuh Tempo Baru</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Aksi</th>

              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              @forelse($data as $item)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ $item->loan->book->title ?? '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($item->new_due_date)->format('d M Y') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                    {{ ucfirst($item->status) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                    <a href="{{ route('perpanjang-peminjaman.detail', ['id' => $item->id]) }}" class="text-blue-600 hover:text-blue-800">
                      Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-gray-500 px-6 py-4">Tidak ada riwayat perpanjangan.</td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
