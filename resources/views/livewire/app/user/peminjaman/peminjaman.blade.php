<div class="mx-auto mb-19 w-10/12">
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Peminjaman Buku</h2>

    @if ($datas->isEmpty())
      <p class="text-center text-gray-500">Tidak ada peminjaman buku saat ini.</p>
    @else
      <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
          <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                  <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                  <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                  <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Jatuh Tempo</th>
                  <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach ($datas as $loan)
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $loan->book->title ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loan->loan_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loan->due_date->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold
                        @if($loan->status === 'borrowed') text-yellow-600
                        @elseif($loan->status === 'returned') text-green-600
                        @else text-gray-600
                        @endif
                      ">
                      {{ ucfirst($loan->status) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                      <a href="{{ route('user.peminjaman.detail', $loan->id) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
