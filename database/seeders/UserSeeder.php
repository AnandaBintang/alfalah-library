<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    if (config('app.env') === 'local') {
      foreach (RoleEnum::cases() as $roleEnum) {
        Role::firstOrCreate(['name' => $roleEnum->value]);
      }
      // Account siswa
      $siswa = User::firstOrCreate(
        ['email' => 'siswa@gmail.com'],
        [
          'name' => 'siswa',
          'is_active' => true,
          'activated_at' => now(),
          'email_verified_at' => now(),
          'expires_at' => now()->addYear(3),
          'password' => Hash::make('password'),
        ]
      );
      $siswa->assignRole(RoleEnum::SISWA->value);

      // Account petugas
      $petugas = User::firstOrCreate(
        ['email' => 'petugas@gmail.com'],
        [
          'name' => 'petugas',
          'password' => Hash::make('password'),
          'is_active' => true,
          'activated_at' => now(),
          'email_verified_at' => now(),
          'expires_at' => now()->addYear(3),
        ]
      );
      $petugas->assignRole(RoleEnum::PETUGAS->value);

      // Account admin
      $admin = User::firstOrCreate(
        ['email' => 'admin@gmail.com'],
        [
          'name' => 'admin',
          'password' => Hash::make('password'),
          'is_active' => true,
          'email_verified_at' => now(),
          'activated_at' => now(),
          'expires_at' => now()->addYear(3),
        ]
      );
      $admin->assignRole(RoleEnum::ADMIN->value);

      // Akun admin
      $perpustakaanAlfalah = User::firstOrCreate(
        ['email' => 'perpustakaansmpalfalahassalam@gmail.com'],
        [
          'name' => 'Admin Perpustakaan SMP Alfalah',
          'password' => Hash::make('password'),
          'email_verified_at' => now(),
          'is_active' => true,
          'activated_at' => now(),
          'expires_at' => now()->addYear(3),
        ]
      );

      $perpustakaanAlfalah->assignRole(RoleEnum::ADMIN->value);

      // User random factory
      User::factory()
        ->count(100)
        ->create()
        ->each(function ($user) {
          $user->assignRole(RoleEnum::SISWA->value);
        });
    }

    if (config('app.env') === 'production') {
      $perpustakaanAlfalah = User::firstOrCreate(
        ['email' => 'perpustakaansmpalfalahassalam@gmail.com'],
        [
          'name' => 'Admin Perpustakaan SMP Alfalah',
          'password' => Hash::make('password'),
          'email_verified_at' => now(),
          'is_active' => 1,
          'activated_at' => now(),
          'expires_at' => now()->addYear(3),
        ]
      );

      $perpustakaanAlfalah->assignRole(RoleEnum::ADMIN->value);
    }
  }
}
