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
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate(['name' => $roleEnum->value]);
        }
        // Account siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@gmail.com'],
            [
                'name' => 'siswa',
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
            ]
        );
        $petugas->assignRole(RoleEnum::PETUGAS->value);

        // Account admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole(RoleEnum::ADMIN->value);

        // Akun admin
      $perpustakaanAlfalah = User::firstOrCreate(
        ['email' => 'perpustakaansmpalfalahassalam@gmail.com'],
        [
          'name' => 'Admin Perpustakaan SMP Alfalah',
          'password' => Hash::make('password'),
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
}
