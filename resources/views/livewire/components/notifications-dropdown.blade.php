<div wire:poll.10s class="hs-dropdown [--placement:bottom-right] relative inline-flex">
  <button id="hs-dropdown-notifications" type="button"
          class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
          aria-haspopup="menu" aria-expanded="false" aria-label="Notifikasi">
    🔔
    <span class="text-xs bg-red-600 text-white rounded-full px-1.5 py-0.5">
      {{ $notifications->count() }}
    </span>
    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke="currentColor">
      <path d="M6 9l6 6 6-6"/>
    </svg>
  </button>

  <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-72 bg-white shadow-md rounded-lg mt-2 z-50 max-h-96 overflow-y-auto"
       role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-notifications">
    <div class="p-1 divide-y divide-gray-200">

      @if ($notifications->count())
        <div class="flex justify-end px-3 py-2">
          <button wire:click="markAllAsRead" class="text-xs text-blue-600 hover:underline">
            Tandai semua dibaca
          </button>
        </div>
      @endif

      @forelse ($notifications as $notification)
        <div class="py-3 px-4 rounded-lg flex items-start gap-x-3
          @if(($notification->data['status'] ?? '') === 'success') bg-green-50
          @elseif(($notification->data['status'] ?? '') === 'pending') bg-yellow-50
          @else bg-red-50
          @endif
          hover:bg-opacity-80 transition">

          <div class="text-xl mt-1">
            @if(($notification->data['status'] ?? '') === 'success')
              ✅
            @elseif(($notification->data['status'] ?? '') === 'pending')
              ⏳
            @else
              ❌
            @endif
          </div>

          <div class="flex-1">
            <div class="text-sm font-medium
              @if(($notification->data['status'] ?? '') === 'success') text-green-700
              @elseif(($notification->data['status'] ?? '') === 'pending') text-yellow-700
              @else text-red-700
              @endif">
              {{ $notification->data['message'] }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
              {{ $notification->created_at->diffForHumans() }}
            </div>
          </div>

          <button wire:click="markAsRead('{{ $notification->id }}')"
                  class="ml-2 text-blue-600 text-xs hover:underline whitespace-nowrap">
            Tandai dibaca
          </button>
        </div>
      @empty
        <div class="py-3 px-4 text-gray-500 text-sm text-center">
          Tidak ada notifikasi baru
        </div>
      @endforelse

    </div>
  </div>
</div>
