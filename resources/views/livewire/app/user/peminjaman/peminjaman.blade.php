<div class="mx-auto mb-19 w-10/12" wire:poll.visible>
  <div class="bg-white border border-gray-300 shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Peminjaman Buku</h2>

    <div class="flex flex-col">
      <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
          <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
              <tr>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Tanggal Jatuh Tempo</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status Buku</th>
                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status Admin</th>
                <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Aksi</th>
              </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
              @forelse ($datas as $loan)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                    {{ $loan->book->title ?? '-' }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ $loan->loan_date->format('d M Y') }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                    {{ $loan->due_date->format('d M Y') }}
                  </td>

                  {{-- Status Buku --}}
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                    @php
                      $status = $loan->loan_status;
                      $statusColor = match($status) {
                          'borrowed'  => 'bg-yellow-500 text-white',
                          'rejected'  => 'bg-red-500 text-white',
                          'returned'  => 'bg-teal-500 text-white',
                          'overdue'   => 'bg-orange-600 text-white',
                          'pending'   => 'bg-gray-500 text-white',
                          default     => 'bg-gray-300 text-gray-800',
                      };
                    @endphp

                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $statusColor }}">
                      {{ strtoupper($status) }}
                    </span>
                  </td>

                  {{-- Status Admin --}}
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                    @php
                      $confirmation = $loan->confirmation_status;
                      $confirmationColor = match($confirmation) {
                          'approved' => 'bg-teal-500 text-white',
                          'pending'  => 'bg-gray-500 text-white',
                          'rejected' => 'bg-red-600 text-white',
                          default    => 'bg-gray-300 text-gray-800',
                      };
                    @endphp

                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $confirmationColor }}">
                      {{ strtoupper($confirmation) }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                    <a href="{{ route('peminjaman.detail', $loan->id) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-gray-500 px-6 py-4">Tidak ada peminjaman buku saat ini.</td>
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
