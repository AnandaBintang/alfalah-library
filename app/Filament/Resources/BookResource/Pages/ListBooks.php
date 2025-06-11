<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
  protected static string $resource = BookResource::class;

  protected function getHeaderActions(): array
  {
    return [
      \EightyNine\ExcelImport\ExcelImportAction::make()
        ->sampleExcel(
          sampleData: [
            'title' => 'Pemrograman Laravel',
            'subtitle' => 'Panduan Lengkap',
            'isbn' => '978-0987654321',
            'publication_year' => 2023,
            'stock' => 10,
            'rack_location' => '2',
            'type' => 'Teknologi',
            'is_student_work' => true,
            'source' => 'Pembelian',
            'catalog_code' => 'K002',
            'classification_code' => '005.133',
            'subject' => 'Pemrograman',
            'abstract' => 'Panduan lengkap belajar Laravel dari dasar sampai mahir',

          ],
          fileName: 'sample-books.xlsx',
          sampleButtonLabel: 'Download Sample',
        )
        ->color("primary")
      ->label("Import to Excel"),
      Actions\CreateAction::make(),
    ];
  }
}
