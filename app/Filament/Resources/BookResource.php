<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
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
      ])
      ->bulkActions([
        ExportBulkAction::make()->label('Export to Excel'),
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

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }

}
