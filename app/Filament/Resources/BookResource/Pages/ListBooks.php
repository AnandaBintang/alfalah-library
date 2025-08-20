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
            'stock' => 10,
            'is_ebook' => 1, // 1 = ebook, 0 = bukan ebook
            'ebook_type' => 'pdf', // 'pdf' atau 'link'
            'ebook_link' => 'https://example.com/laravel-ebook',
            'ebook_file_path' => 'ebooks/laravel.pdf',
            'catalog_code' => 'K002',
            'publication_year' => 2023,
            'rack_location' => 4,
            'abstract' => 'Panduan lengkap belajar Laravel dari dasar sampai mahir',
          ],
          fileName: 'sample-books.xlsx',
          sampleButtonLabel: 'Download Sample',
        )
        ->validateUsing([
          'title' => 'required|string|max:255',
          'subtitle' => 'nullable|string',
          'isbn' => 'nullable|string|max:255|unique:books,isbn',
          'stock' => 'required|integer|min:0',
          'is_ebook' => 'required|boolean',
          'ebook_type' => 'nullable|in:link,pdf',
          'ebook_link' => 'nullable|required_if:ebook_type,link|string',
          'ebook_file_path' => 'nullable|required_if:ebook_type,pdf|string|max:255',
          'catalog_code' => 'nullable|string|max:255',
          'publication_year' => 'nullable|integer|min:1000|max:' . now()->year,
          'rack_location' => 'nullable|integer',
          'abstract' => 'nullable|string',
        ])
        ->color("primary")
        ->label("Import ke Excel"),
      Actions\CreateAction::make(),
    ];
  }
}
