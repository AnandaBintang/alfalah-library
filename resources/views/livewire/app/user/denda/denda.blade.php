<div class="mx-auto mb-19 w-10/12" wire:poll.visible>
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Denda Buku</h2>

    <div class="flex flex-col">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Buku</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Jumlah Denda</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Detail</th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              @forelse ($fines as $fine)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ $fine->loan->book->title ?? '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    Rp{{ number_format($fine->amount, 0, ',', '.') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ \Carbon\Carbon::parse($fine->loan->loan_date)->format('d M Y') ?? '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if ($fine->status === 'paid')
                      <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-500 text-white">
                        {{ ucfirst($fine->status) }}
                      </span>
                    @else
                      <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">
                        {{ ucfirst($fine->status) }}
                      </span>
                    @endif
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                    <a href="{{ route('denda.detail', ['id' => $fine->id]) }}"
                       wire:navigate
                       class="text-blue-600 hover:text-blue-800">
                      Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-gray-500 px-6 py-4">Tidak ada data denda.</td>
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
