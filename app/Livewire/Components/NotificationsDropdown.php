<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsDropdown extends Component
{
  public function markAsRead($notificationId)
  {
    $notification = Auth::user()->notifications()->find($notificationId);

    if ($notification) {
      $notification->markAsRead();
    }
  }

  public function markAllAsRead()
  {
    Auth::user()->unreadNotifications->markAsRead();
  }

  public function render()
  {
    return view('livewire.components.notifications-dropdown', [
      'notifications' => Auth::user()->unreadNotifications()->get(),
    ]);
  }
}
