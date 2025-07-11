<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(public $status, public $message)
  {

  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['database'];
  }

  public function toDatabase($notifiable): array
  {
    if ($this->status === 'success') {
      return [
        'message' => $this->message,
      ];
    }

    if ($this->status === 'pending') {
      return [
        'message' => $this->message,
      ];
    }

    return [
      'message' => $this->message,
    ];

  }

}
