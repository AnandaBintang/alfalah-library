<x-filament-widgets::widget>
  <x-filament::section>
    <div class="space-y-6">
      <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Panduan Status Peminjaman</h2>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Status Buku --}}
        <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
          <h3 class="text-md font-bold text-gray-800 dark:text-gray-200">Status Buku</h3>
          <ul class="space-y-2 mt-2">
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-blue-500 rounded-full"></span>
              <strong class="mr-2">Pending</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Menunggu persetujuan admin.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-green-500 rounded-full"></span>
              <strong class="mr-2">Borrowed</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Buku sedang dipinjam.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-gray-500 rounded-full"></span>
              <strong class="mr-2">Returned</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Buku telah dikembalikan.</span>
            </li>
          </ul>
        </div>

        {{-- Status Admin --}}
        <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
          <h3 class="text-md font-bold text-gray-800 dark:text-gray-200">Status Admin</h3>
          <ul class="space-y-2 mt-2">
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-blue-500 rounded-full"></span>
              <strong class="mr-2">Pending</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Menunggu persetujuan admin.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-green-500 rounded-full"></span>
              <strong class="mr-2">Approved</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Disetujui oleh admin.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-red-500 rounded-full"></span>
              <strong class="mr-2">Rejected</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Ditolak oleh admin.</span>
            </li>
          </ul>
        </div>

        {{-- Status Pengembalian --}}
        <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
          <h3 class="text-md font-bold text-gray-800 dark:text-gray-200">Status Pengembalian</h3>
          <ul class="space-y-2 mt-2">
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-green-500 rounded-full"></span>
              <strong class="mr-2">On Time</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Dikembalikan tepat waktu.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-red-500 rounded-full"></span>
              <strong class="mr-2">Overdue</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Dikembalikan terlambat.</span>
            </li>
            <li class="flex items-center">
              <span class="inline-block w-2.5 h-2.5 mr-2 bg-yellow-500 rounded-full"></span>
              <strong class="mr-2">Not Returned</strong>:
              <span class="text-sm text-gray-600 dark:text-gray-400">Belum dikembalikan.</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </x-filament::section>
</x-filament-widgets::widget>
