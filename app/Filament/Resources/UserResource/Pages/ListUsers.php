<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enum\RoleEnum;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
//      \EightyNine\ExcelImport\ExcelImportAction::make()
//        ->sampleExcel(
//          sampleData: [
//            'name' => 'Budi Gunadi',
//            'email' => 'budigunadi@gmail.com',
//            'password' => 'password',
//          ],
//          fileName: 'sample-users.xlsx',
//          sampleButtonLabel: 'Download Sample',
//        )
//        ->afterImport(function ($records) {
//          foreach ($records as $record) {
//            $record->assignRole(\App\Enum\RoleEnum::SISWA->value);
//          }
//        })
//        ->validateUsing([
//          'name' => 'required|string',
//          'email' => 'required|email|unique:users,email',
//          'password' => 'required|string',
//        ])
//        ->color("primary")
//        ->label("Import Excel"),
      Actions\CreateAction::make(),


    ];
  }
}
