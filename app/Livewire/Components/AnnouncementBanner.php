<?php

namespace App\Livewire\Components;

use App\Models\Announcement;
use Livewire\Component;

class AnnouncementBanner extends Component
{
    public function render()
    {
        return view('livewire.components.announcement-banner', [
            'announcement' => Announcement::where('is_published', 1)->first(),
        ]);
    }
}
