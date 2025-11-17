<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
  protected static string $resource = AbsensiResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
      Actions\Action::make('scan_absensi')
        ->label('Scan Absensi')
        ->icon('heroicon-o-qr-code')
        ->color('success')
        ->url(route('responsi.scanner'))
        ->openUrlInNewTab(),
    ];
  }
}
