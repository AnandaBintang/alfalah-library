<div class="mx-auto mb-19 w-10/12">
  <div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name Donatur</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Image</th>
              <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Title</th>
              <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Quantity</th>
              <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Status Donasi</th>
              <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Tanggal Pengajuan
              </th>
              <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Detail Donasi</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $data->quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                  @if($data->status === \App\Enum\ApprovalStatusEnum::PENDING->value)
                    <span
                      class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-500 text-white">{{ $data->status }}</span>
                  @elseif($data->status === \App\Enum\ApprovalStatusEnum::REJECTED->value)
                    <span
                      class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-500 text-white">{{ $data->status }}</span>
                  @elseif($data->status === \App\Enum\ApprovalStatusEnum::APPROVED->value)
                    <span
                      class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-500 text-white">{{ $data->status }}</span>
                  @endif
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($data->donation_date)->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                  <a href="{{ route('donasi.detail', ['id' => $data->id]) }}"
                     class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                    Detail
                  </a>
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
