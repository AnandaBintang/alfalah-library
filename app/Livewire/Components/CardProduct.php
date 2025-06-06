<?php

namespace App\Livewire\Components;

use Livewire\Component;

class CardProduct extends Component
{
    public $cardTitle;

    public $description;

    public $image;

    public $link;

    public function mount($cardTitle = null, $description = null, $image = null, $link = null)
    {
        $this->image = $image;
        $this->cardTitle = $cardTitle;
        $this->description = $description;
        $this->link = $link;
    }

    public function render()
    {
        return view('livewire.components.card-product');
    }
}
