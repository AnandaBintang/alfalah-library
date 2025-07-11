<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\WriterResource\Pages;
use App\Models\Writer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class WriterResource extends Resource
{
  protected static ?string $model = Writer::class;
  protected static ?string $navigationIcon = 'heroicon-o-user-circle';
  protected static ?string $navigationLabel = 'Penulis';
  protected static ?string $recordTitleAttribute = 'name';
  protected static ?string $pluralModelLabel = 'Penulis';
  protected static ?string $modelLabel = 'Penulis';
  protected static int $globalSearchResultsLimit = 20;

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Buku';
  }

  public static function getGlobalSearchEloquentQuery(): Builder
  {
    return parent::getGlobalSearchEloquentQuery()->with('books');
  }

  public static function getGloballySearchableAttributes(): array
  {
    return ['name', 'email'];
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Informasi Penulis')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Nama Penulis')
              ->required()
              ->maxLength(255)
              ->columnSpanFull(),

            Forms\Components\TextInput::make('email')
              ->label('Email')
              ->email()
              ->maxLength(255)
              ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('phone')
              ->label('Telepon')
              ->tel()
              ->maxLength(20),
          ])
          ->columns(2),

        Forms\Components\Section::make('Informasi Tambahan')
          ->schema([
            Forms\Components\Textarea::make('biography')
              ->label('Biografi')
              ->maxLength(1000)
              ->rows(4)
              ->columnSpanFull(),

            Forms\Components\Textarea::make('address')
              ->label('Alamat')
              ->maxLength(500)
              ->rows(3)
              ->columnSpanFull(),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Penulis')
          ->searchable()
          ->sortable()
          ->weight('bold'),

        Tables\Columns\TextColumn::make('email')
          ->label('Email')
          ->searchable()
          ->default('-')
          ->copyable()
          ->icon('heroicon-o-envelope'),

        Tables\Columns\TextColumn::make('phone')
          ->label('Telepon')
          ->default('-')
          ->copyable()
          ->icon('heroicon-o-phone'),

        Tables\Columns\TextColumn::make('books_count')
          ->label('Jumlah Buku')
          ->counts('books')
          ->badge()
          ->color('info')
          ->alignCenter(),

        Tables\Columns\TextColumn::make('biography')
          ->label('Biografi')
          ->limit(50)
          ->default('-')
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('address')
          ->label('Alamat')
          ->limit(30)
          ->default('-')
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Dibuat')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('name')
      ->filters([
        Tables\Filters\Filter::make('has_books')
          ->label('Memiliki Buku')
          ->query(fn(Builder $query) => $query->has('books')),

        Tables\Filters\Filter::make('has_email')
          ->label('Memiliki Email')
          ->query(fn(Builder $query) => $query->whereNotNull('email')),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('view_books')
          ->label('Lihat Buku')
          ->icon('heroicon-o-book-open')
          ->color('info')
          ->url(
            fn(Writer $record): string =>
            route('filament.admin.resources.books.index', [
              'tableFilters' => ['writer_id' => ['value' => $record->id]]
            ])
          )
          ->visible(fn(Writer $record): bool => $record->books_count > 0),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          ExportBulkAction::make()
            ->label('Export ke Excel'),
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ])
      ->emptyStateActions([
        Tables\Actions\CreateAction::make(),
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
      'index' => Pages\ListWriters::route('/'),
      'create' => Pages\CreateWriter::route('/create'),
      'view' => Pages\ViewWriter::route('/{record}'),
      'edit' => Pages\EditWriter::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && Auth::user()->hasAnyRole([
      RoleEnum::ADMIN->value,
      RoleEnum::PETUGAS->value
    ]);
  }

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::count();
  }
}
