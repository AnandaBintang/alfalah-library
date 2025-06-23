<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Filament\Notifications\Notification;

class BookResource extends Resource
{
  protected static ?string $model = Book::class;

  protected static ?string $navigationIcon = 'heroicon-o-book-open';

  protected static ?string $navigationLabel = 'Buku';

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Buku';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('title')->label('Judul Buku')->required(),
        Forms\Components\TextInput::make('subtitle')->label('Subjudul'),
        Forms\Components\TextInput::make('isbn')->label('ISBN'),
        Forms\Components\Select::make('publisher_id')
          ->label('Penerbit')
          ->relationship('publisher', 'name'),
        Forms\Components\TextInput::make('stock')->label('Stok')->numeric(),
        Forms\Components\TextInput::make('type')->label('Jenis'),
        Forms\Components\Toggle::make('is_student_work')->label('Karya Siswa'),
        Forms\Components\TextInput::make('source')->label('Sumber'),
        Forms\Components\TextInput::make('catalog_code')->label('Kode Katalog'),
        Forms\Components\TextInput::make('publication_year')->label('Tahun Terbit')->numeric(),
        Forms\Components\TextInput::make('classification_code')->label('Kode Klasifikasi'),
        Forms\Components\TextInput::make('rack_location')->label('Lokasi Rak'),
        Forms\Components\TextInput::make('subject')->label('Subjek'),
        Forms\Components\Textarea::make('abstract')->label('Abstrak'),
        Forms\Components\Select::make('categories')
          ->label('Kategori')
          ->relationship('categories', 'name')
          ->multiple(),
        Forms\Components\FileUpload::make('cover_image_path')
          ->label('Cover Buku')
          ->directory('cover-books')
          ->image()
          ->imagePreviewHeight('150')
          ->downloadable(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('cover_image_path')
          ->label('Cover Buku'),
        Tables\Columns\TextColumn::make('title')
          ->label('Judul Buku')
          ->searchable(),
        Tables\Columns\TextColumn::make('author')
          ->label('Penulis'),
        Tables\Columns\TextColumn::make('publication_year')
          ->label('Tahun Terbit'),
        Tables\Columns\TextColumn::make('publisher.name')
          ->label('Penerbit'),
        Tables\Columns\TextColumn::make('isbn')
          ->label('ISBN'),
        Tables\Columns\TextColumn::make('stock')
          ->label('Stok'),
        Tables\Columns\TextColumn::make('rack_location')
          ->label('Lokasi Rak'),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('print_card')
          ->label('Print Kartu')
          ->icon('heroicon-o-printer')
          ->color('success')
          ->url(fn(Book $record): string => route('book.print-card', $record))
          ->openUrlInNewTab(),
      ])
      ->bulkActions([
        ExportBulkAction::make()->label('Export to Excel'),
        Tables\Actions\BulkAction::make('print_cards')
          ->label('Print Kartu Buku')
          ->icon('heroicon-o-printer')
          ->color('success')
          ->action(function ($records) {
            if ($records->count() > 30) {
              Notification::make()
                ->title('Terlalu Banyak Buku')
                ->body('Maksimal 30 buku yang dapat dicetak sekaligus.')
                ->danger()
                ->send();
              return;
            }

            $bookIds = $records->pluck('id')->toArray();
            $url = route('book.print-cards-bulk', ['ids' => implode(',', $bookIds)]);

            return redirect($url);
          })
          ->deselectRecordsAfterCompletion(),
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }


  public static function getPages(): array
  {
    return [
      'index' => Pages\ListBooks::route('/'),
      'create' => Pages\CreateBook::route('/create'),
      'edit' => Pages\EditBook::route('/{record}/edit'),
    ];
  }

  public function generateCard($id)
  {
    $book = Book::findOrFail($id);
    $rackLocationParts = explode('.', $book->rack_location);
    $rackCode = isset($rackLocationParts[0]) ? str_pad($rackLocationParts[0], 3, '0', STR_PAD_LEFT) : '';
    $publisherCode = '';

    if ($book->publisher && !empty($book->publisher->name)) {
      $publisherParts = explode(' ', $book->publisher->name);
      $publisherCode = strtoupper(substr(implode('', array_map(function ($part) {
        return substr($part, 0, 1);
      }, $publisherParts)), 0, 3));
    }

    $titleCode = strtoupper(substr($book->title, 0, 1));
    $bookNumber = str_pad($book->id, 3, '0', STR_PAD_LEFT);
    $libraryCardCode = "{$rackCode}.{$bookNumber} {$publisherCode} {$titleCode}";

    return response()->json([
      'classification_code' => $rackCode . '.' . $bookNumber,
      'publisher_code' => $publisherCode,
      'title_code' => $titleCode,
      'library_card_code' => $libraryCardCode,
      'book' => new BookResource($book)
    ]);
  }

  public function printCard($id)
  {
    $book = Book::findOrFail($id);
    $cardData = $this->generateCardData($book);

    return view('book-card.print', compact('cardData'));
  }

  public function printCardsBulk($ids)
  {
    $bookIds = explode(',', $ids);

    if (count($bookIds) > 30) {
      return redirect()->back()->with('error', 'Maksimal 30 buku yang dapat dicetak sekaligus.');
    }

    $books = Book::whereIn('id', $bookIds)->get();
    $cardsData = [];

    foreach ($books as $book) {
      $cardsData[] = $this->generateCardData($book);
    }

    return view('book-card.print-bulk', compact('cardsData'));
  }

  private function generateCardData($book)
  {
    $rackLocationParts = explode('.', $book->rack_location);
    $rackCode = isset($rackLocationParts[0]) ? str_pad($rackLocationParts[0], 3, '0', STR_PAD_LEFT) : '';
    $publisherCode = '';

    if ($book->publisher && !empty($book->publisher->name)) {
      $publisherParts = explode(' ', $book->publisher->name);
      $publisherCode = strtoupper(substr(implode('', array_map(function ($part) {
        return substr($part, 0, 1);
      }, $publisherParts)), 0, 3));
    }

    $titleCode = strtoupper(substr($book->title, 0, 1));
    $bookNumber = str_pad($book->id, 3, '0', STR_PAD_LEFT);
    $classificationCode = $rackCode . '.' . $bookNumber;

    return [
      'classification_code' => $classificationCode,
      'title_code' => $titleCode,
      'publisher_code' => $publisherCode,
      'book' => $book
    ];
  }
}
