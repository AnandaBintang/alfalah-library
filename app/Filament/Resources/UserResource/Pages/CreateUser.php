<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
  protected static string $resource = UserResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $this->roleToAssign = $data['role'] ?? null;

    unset($data['role']);

    return $data;
  }

  protected function afterCreate(): void
  {
    if ($this->roleToAssign) {
      $this->record->assignRole($this->roleToAssign);

      if (in_array($this->roleToAssign, [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
        $this->record->update([
          'is_active' => true,
          'activated_at' => now(),
          'expires_at' => null,
        ]);
      }
    }
  }

  protected $roleToAssign;
}
