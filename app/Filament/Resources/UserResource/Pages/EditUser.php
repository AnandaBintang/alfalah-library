<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
  protected static string $resource = UserResource::class;

  public function mount(string|int $record): void
  {
    if (!Auth::check() || !Auth::user()->hasRole(RoleEnum::ADMIN->value)) {
      $this->redirect(route('login'));
    }

    parent::mount($record);
  }


  protected function handleRecordUpdate(Model $record, array $data): Model
  {
    $record->update($data);
    if (isset($data['role'])) {
      $record->syncRoles($data['role']);
    }
    return $record;
  }

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }
}
