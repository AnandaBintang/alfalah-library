<?php

namespace App\Filament\Imports;

use App\Enum\RoleEnum;
use App\Models\Profile;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserWithProfileImporter extends Importer
{
  protected static ?string $model = User::class;

  protected static ?string $label = 'Import User & Profile';

  public static function getColumns(): array
  {
    return [
      ImportColumn::make('name')
        ->label('Nama Lengkap')
        ->requiredMapping()
        ->rules(['required', 'string', 'max:255']),

      ImportColumn::make('email')
        ->label('Email')
        ->requiredMapping()
        ->rules(['required', 'email', 'unique:users,email']),

      ImportColumn::make('password')
        ->label('Password')
        ->requiredMapping()
        ->rules(['required', 'string', 'min:6']),

      ImportColumn::make('nis')
        ->label('NIS')
        ->requiredMapping()
        ->rules(['required', 'string', 'unique:profiles,nis', 'max:20']),

      ImportColumn::make('nisn')
        ->label('NISN')
        ->rules(['nullable', 'string', 'max:20']),

      ImportColumn::make('class')
        ->label('Kelas')
        ->requiredMapping()
        ->rules(['required', 'string', 'max:10']),

      ImportColumn::make('gender')
        ->label('Jenis Kelamin')
        ->requiredMapping()
        ->rules(['required', Rule::in(['L', 'P'])]),

      ImportColumn::make('address')
        ->label('Alamat')
        ->rules(['nullable', 'string', 'max:500']),

      ImportColumn::make('phone')
        ->label('No. HP')
        ->rules(['nullable', 'string', 'max:15']),
    ];
  }

  public function resolveRecord(): ?User
  {
    // Cek apakah user sudah ada berdasarkan email
    $existingUser = User::where('email', $this->data['email'])->first();

    if ($existingUser) {
      // Skip jika user sudah ada
      return null;
    }

    // Cek apakah NIS sudah ada
    $existingProfile = Profile::where('nis', $this->data['nis'])->first();

    if ($existingProfile) {
      // Skip jika NIS sudah ada
      return null;
    }

    // Buat user baru
    $user = User::create([
      'name' => $this->data['name'],
      'email' => $this->data['email'],
      'password' => Hash::make($this->data['password']),
      'is_active' => false, // Default tidak aktif, butuh aktivasi manual
      'activated_at' => null,
      'expires_at' => null,
    ]);

    // Buat profile
    Profile::create([
      'user_id' => $user->id,
      'nis' => $this->data['nis'],
      'nisn' => $this->data['nisn'] ?? null,
      'class' => $this->data['class'],
      'gender' => $this->data['gender'],
      'address' => $this->data['address'] ?? null,
      'phone' => $this->data['phone'] ?? null,
    ]);

    return $user;
  }

  public static function getCompletedNotificationBody(Import $import): string
  {
    $body = 'Import user selesai. ' . number_format($import->successful_rows) . ' user berhasil diimport.';

    if ($failedRowsCount = $import->getFailedRowsCount()) {
      $body .= ' ' . number_format($failedRowsCount) . ' user gagal diimport.';
    }

    return $body;
  }
}
