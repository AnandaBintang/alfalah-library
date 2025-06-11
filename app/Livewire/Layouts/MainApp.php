<?php

namespace App\Livewire\Layouts;

use App\Models\Cart;
use App\Models\CartItem;
use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use Livewire\Component;

class MainApp extends Component
{
  public int $pendingCount = 0;

  public function mount()
  {
    $this->refreshCount();
  }

  public function hydrate()
  {
    $this->refreshCount();
  }

  protected function refreshCount(): void
  {
    $this->pendingCount = CartItem::query()
      ->whereHas('cart', fn($q) => $q->where('user_id', auth()->id())
        ->where('status', StatusCartEnum::PENDING->value))
      ->where('status', StatusCartItemEnum::BOOKED->value)
      ->count();
  }

  public function render()
  {
    return view('livewire.layouts.main-app', [
      'cart' => $this->pendingCount,
    ]);
  }
}
