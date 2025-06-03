<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Account siswa
    User::firstOrCreate(
      ['email' => 'siswa@gmail.com'],
      [
        'name' => 'siswa',
        'password' => Hash::make('password'),
        'role' => RoleEnum::SISWA->value,
      ]
    );

    // Account petugas
    User::firstOrCreate(
      ['email' => 'petugas@gmail.com'],
      [
        'name' => 'petugas',
        'password' => Hash::make('password'),
        'role' => RoleEnum::PETUGAS->value,
      ]
    );

    // Account admin
    User::firstOrCreate(
      ['email' => 'admin@gmail.com'],
      [
        'name' => 'admin',
        'password' => Hash::make('password'),
        'role' => RoleEnum::ADMIN->value,
      ]
    );

    User::factory()->count(10)->create();
  }
}
