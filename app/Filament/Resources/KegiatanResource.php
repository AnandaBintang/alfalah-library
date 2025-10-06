<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\KegiatanResource\Pages;
use App\Filament\Resources\KegiatanResource\RelationManagers;
use App\Models\Kegiatan;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KegiatanResource extends Resource
{
  protected static ?string $model = Kegiatan::class;

  protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
  protected static ?string $navigationLabel = 'Kegiatan Landing Page';

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Landing Page';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Kegiatan Perpustakaan')
          ->schema([
            Forms\Components\TextInput::make('nama')
              ->label('Nama Kegiatan')
              ->required(),
            Forms\Components\Textarea::make('deskripsi')
              ->label('Deskripsi Kegiatan'),
            Forms\Components\Select::make('is_active')
              ->label('Status Kegiatan')
              ->required()
              ->options([
                1 => 'Aktif',
                0 => 'Tidak Aktif',
              ])
              // Pilihan opsional: atur nilai default
              ->default(1),
            Forms\Components\FileUpload::make('image')
              ->maxSize(5120)
              ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'])
              ->label('Gambar Kegiatan')
              ->directory('kegiatan')
              ->required(),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\ImageColumn::make('image')
          ->label('Gambar Kegiatan')
        ,
        Tables\Columns\TextColumn::make('nama')
          ->label('Nama Kegiatan'),
        Tables\Columns\TextColumn::make('deskripsi')
          ->label('Deskripsi Kegiatan'),

        Tables\Columns\TextColumn::make('is_active')
          ->label('Status')
          ->badge()
          ->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')
          ->color(fn(bool $state): string => $state ? 'success' : 'danger'),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Dibuat Pada')
          ->dateTime()
      ])
      ->filters([
        Tables\Filters\TrashedFilter::make(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        DeleteAction::make(),
        ForceDeleteAction::make(),
        RestoreAction::make()
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
      'index' => Pages\ListKegiatans::route('/'),
      'create' => Pages\CreateKegiatan::route('/create'),
      'edit' => Pages\EditKegiatan::route('/{record}/edit'),
    ];
  }
}
