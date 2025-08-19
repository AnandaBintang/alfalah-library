<div class="mx-auto mb-19 w-10/12" wire:poll.visible>
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Donasi</h2>

    <div class="flex flex-col">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
              <tr>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Nama Donatur
                </th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Cover</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status Donasi
                </th>
                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal
                  Pengajuan
                </th>
                <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Detail Donasi
                </th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              @forelse($datas as $data)
                <tr>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ \Illuminate\Support\Facades\Auth::user()->name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    <img src="{{ asset('storage/' . $data->image) }}" alt="image" class="w-16 h-auto rounded">
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $data->item_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-end">{{ $data->quantity }}</td>
                  @if($data->status == \App\Enum\ApprovalStatusEnum::PENDING->value )
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">
                      <span
                        class="inline-flex items-center justify-center py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-500 text-white">
                        {{ ucfirst($data->status) }}
                      </span>
                    </td>
                  @elseif($data->status == \App\Enum\ApprovalStatusEnum::APPROVED->value)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">
                      <span
                        class="inline-flex items-center justify-center py-1.5 px-3 rounded-full text-xs font-medium bg-teal-500 text-white">
                        {{ ucfirst($data->status) }}
                      </span>
                    </td>
                  @else
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center">
                      <span
                        class="inline-flex items-center justify-center py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">
                        {{ ucfirst($data->status) }}
                      </span>
                    </td>
                  @endif


                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($data->donation_date)->format('d M Y') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                    <a href="{{ route('donasi.detail', ['id' => $data->id]) }}"
                       class="text-blue-600 hover:text-blue-800">Detail</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-gray-500 px-6 py-4">Tidak ada data donasi.</td>
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
