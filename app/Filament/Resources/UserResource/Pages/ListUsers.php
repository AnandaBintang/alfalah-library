<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

  public function mount(): void
  {
    if (!Auth::check() || !Auth::user()->hasRole(RoleEnum::ADMIN->value)) {
      $this->redirect(route('login'));
    }
  }


  protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
