<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
  protected static string $resource = UserResource::class;

  public function mount(): void
  {
    if (!Auth::check() || !Auth::user()->hasRole(RoleEnum::ADMIN->value)) {
     $this->redirect(route('login'));
    }
  }


  protected function handleRecordCreation(array $data): User
  {
    $user = User::create($data);
    if (isset($data['role'])) {
      $user->syncRoles($data['role']);
    }
    return $user;
  }
}
