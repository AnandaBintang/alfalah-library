<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
  protected static string $resource = UserResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

  protected function mutateFormDataBeforeFill(array $data): array
  {
    $data['role'] = $this->record->roles->first()?->name;

    return $data;
  }

  protected function mutateFormDataBeforeSave(array $data): array
  {
    $this->newRole = $data['role'] ?? null;

    unset($data['role']);

    return $data;
  }

  protected function afterSave(): void
  {
    if ($this->newRole) {
      $this->record->syncRoles([]);

      $this->record->assignRole($this->newRole);

      if (in_array($this->newRole, [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
        $this->record->update([
          'is_active' => true,
          'activated_at' => now(),
          'expires_at' => null,
        ]);
      }
    }
  }

  protected $newRole;
}
