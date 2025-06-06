<?php

namespace App\Trait;

use WireUi\Traits\WireUiActions;

trait NotificationsAndDialog
{
    use WireUiActions;

    public function infoNotification($title, $description): void
    {
        $this->notification()->send([
            'icon' => 'info',
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function errorNotification($title, $description): void
    {
        $this->notification()->send([
            'icon' => 'error',
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function successNotification($title, $description): void
    {
        $this->notification()->send([
            'icon' => 'success',
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function infoDialog($title, $description): void
    {
        $this->dialog()->show([
            'icon' => 'info',
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function errorDialog($title, $description): void
    {
        $this->dialog()->show([
            'icon' => 'error',
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function successDialog($title, $description): void
    {
        $this->dialog()->show([
            'icon' => 'success',
            'title' => $title,
            'description' => $description,
        ]);
    }
}
