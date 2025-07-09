<div class="fixed top-0 left-0 w-full z-50 bg-yellow-100 text-yellow-800 py-3 px-4 text-sm font-medium overflow-hidden shadow-md" wire:poll.visible>
  <div class="animate-marquee whitespace-nowrap">
    {{ $announcement->content ?? "📢 Announcement" }}
  </div>
</div>