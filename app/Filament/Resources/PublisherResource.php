<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\PublisherResource\Pages;
use App\Models\Publisher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PublisherResource extends Resource
{
  protected static ?string $model = Publisher::class;
  protected static ?string $navigationIcon = 'heroicon-o-building-office';
  protected static ?string $navigationLabel = 'Penerbit';
  protected static ?string $recordTitleAttribute = 'name';
  protected static ?string $pluralModelLabel = 'Penerbit';
  protected static ?string $modelLabel = 'Penerbit';
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
    return ['name', 'address'];
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Informasi Penerbit')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Nama Penerbit')
              ->required()
              ->maxLength(255)
              ->columnSpanFull(),

            Forms\Components\TextInput::make('phone')
              ->label('Telepon')
              ->tel()
              ->maxLength(20),
          ])
          ->columns(2),

        Forms\Components\Section::make('Alamat')
          ->schema([
            Forms\Components\Textarea::make('address')
              ->label('Alamat Lengkap')
              ->maxLength(500)
              ->rows(4)
              ->columnSpanFull(),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Penerbit')
          ->searchable()
          ->sortable()
          ->weight('bold'),

        Tables\Columns\TextColumn::make('phone')
          ->label('Telepon')
          ->default('-')
          ->copyable()
          ->icon('heroicon-o-phone'),

        Tables\Columns\TextColumn::make('address')
          ->label('Alamat')
          ->limit(40)
          ->default('-')
          ->wrap(),

        Tables\Columns\TextColumn::make('books_count')
          ->label('Jumlah Buku')
          ->counts('books')
          ->badge()
          ->color('success')
          ->alignCenter(),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Dibuat')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('updated_at')
          ->label('Diubah')
          ->dateTime('d M Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->defaultSort('name')
      ->filters([
        Tables\Filters\Filter::make('has_books')
          ->label('Memiliki Buku')
          ->query(fn(Builder $query) => $query->has('books')),

        Tables\Filters\Filter::make('has_phone')
          ->label('Memiliki Telepon')
          ->query(fn(Builder $query) => $query->whereNotNull('phone')),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('view_books')
          ->label('Lihat Buku')
          ->icon('heroicon-o-book-open')
          ->color('info')
          ->url(
            fn(Publisher $record): string =>
            route('filament.admin.resources.books.index', [
              'tableFilters' => ['publisher_id' => ['value' => $record->id]]
            ])
          )
          ->visible(fn(Publisher $record): bool => $record->books_count > 0),
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
      'index' => Pages\ListPublishers::route('/'),
      'create' => Pages\CreatePublisher::route('/create'),
      'view' => Pages\ViewPublisher::route('/{record}'),
      'edit' => Pages\EditPublisher::route('/{record}/edit'),
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
