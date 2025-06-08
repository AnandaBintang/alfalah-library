<div class="bg-yellow-100 text-yellow-800 py-3 px-4 text-sm font-medium overflow-hidden relative" wire:poll.visible>
  <div class="animate-marquee whitespace-nowrap">
    {{ $announcement->content ?? "📢 Announcement" }}
  </div>
</div>
