<?php

namespace App\Filament\Resources\LoanExtensionResource\Pages;

use App\Filament\Resources\LoanExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanExtensions extends ListRecords
{
  protected static string $resource = LoanExtensionResource::class;
  protected static ?string $title = 'Daftar Perpanjangan Peminjaman';

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }
}
