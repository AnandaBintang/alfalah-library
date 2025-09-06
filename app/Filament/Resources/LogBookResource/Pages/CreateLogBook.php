<?php

namespace App\Filament\Resources\LogBookResource\Pages;

use App\Filament\Resources\LogBookResource;
use App\Models\Book;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateLogBook extends CreateRecord
{
    protected static string $resource = LogBookResource::class;

  protected function afterCreate(): void
  {
    DB::beginTransaction();

    try {
      $buku = Book::find($this->record->buku_id);

      if ($buku) {
        $buku->decrement('stock', $this->record->jumlah);
      }

      DB::commit();
    } catch (\Throwable $e) {
      \Log::channel('errorlog')->error("Error when trying to create log book {$e->getMessage()}");
      DB::rollBack();
      return;
    }
  }
}
