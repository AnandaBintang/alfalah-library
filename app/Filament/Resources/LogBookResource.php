<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogBookResource\Pages;
use App\Filament\Resources\LogBookResource\RelationManagers;
use App\Models\Book;
use App\Models\KondisiBook;
use App\Models\LogBook;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LogBookResource extends Resource
{
  protected static ?string $model = KondisiBook::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel = 'Log Kondisi Buku';

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Buku';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make("Laporan Buku Hilang/Rusak")
          ->schema([
            Forms\Components\Select::make('book.title')
              ->label('Judul Buku')
              ->searchable()
              ->getSearchResultsUsing(fn(?string $search) => Book::query()
                ->where('title', 'like', "%{$search}%")
                ->limit(20)
                ->pluck('title', 'id')
                ->all()
              )
              ->getOptionLabelUsing(fn($value): ?string => Book::find($value)?->title)
              ->loadingMessage('Loading...')
              ->noSearchResultsMessage('Buku tidak ditemukan.')
              ->required(),

            Forms\Components\Radio::make('status')
              ->label('Status')
              ->options([
                'HILANG' => 'Hilang',
                'RUSAK' => 'Rusak',
              ])
              ->required(),

            TextInput::make('notes')
              ->label('Catatan')
              ->required(),

            Forms\Components\DatePicker::make('reported_at')
              ->label('Tanggal Laporan')
              ->required(),

          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('book.title')
          ->label("Judul Buku")
        ->searchable(),
        Tables\Columns\TextColumn::make('status')
          ->label("Status"),
        Tables\Columns\TextColumn::make('notes')
          ->label("Catatan"),
        Tables\Columns\TextColumn::make('reported_at')
          ->label("Tanggal Laporan")
          ->dateTime()
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('reported_at')
          ->label('Urutkan berdasarkan')
          ->options([
            'newest' => 'Terbaru',
            'oldest' => 'Terlama',
          ])
          ->query(function ($query, $state) {
            return match ($state) {
              'newest' => $query->orderBy('reported_at', 'desc'),
              'oldest' => $query->orderBy('reported_at', 'asc'),
              default => $query,
            };
          }),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\ViewAction::make("Detail")
        ->infolist([
          Section::make("Laporan Buku Hilan/Rusak")
          ->schema([
            Tables\Columns\TextColumn::make('book.title')
            ->label("Judul Buku"),
            Tables\Columns\TextColumn::make('status')
            ->label("Status"),
            Tables\Columns\TextColumn::make('notes')
            ->label("Catatan"),
            Tables\Columns\TextColumn::make('reported_at')
            ->label("Tanggal Laporan")
          ])
        ])
      ])
      ->bulkActions([
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
      'index' => Pages\ListLogBooks::route('/'),
      'create' => Pages\CreateLogBook::route('/create'),
      'edit' => Pages\EditLogBook::route('/{record}/edit'),
    ];
  }
}
