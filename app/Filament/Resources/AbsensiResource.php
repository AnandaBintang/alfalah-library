<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\AbsensiResource\Pages;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
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

class AbsensiResource extends Resource
{
  protected static ?string $model = Absensi::class;

  protected static ?string $navigationIcon = 'heroicon-o-clock';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Kunjungan Perpustakaan')
          ->schema([
            Forms\Components\Select::make('user_id')
              ->relationship(
                name: 'user',
                titleAttribute: 'name',
                modifyQueryUsing: fn($query) => $query->role(RoleEnum::SISWA->value
                ))
              ->searchable()
              ->label('Nama Siswa')
              ->preload()
              ->required(),
            Forms\Components\DatePicker::make('absensi_tanggal')
              ->required()
              ->label('Tanggal Kunjungan'),
            Forms\Components\TextInput::make('absensi_keterangan')
              ->label('Keterangan'),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('user.name')
        ->label('Nama Siswa')
        ->searchable(),
        Tables\Columns\TextColumn::make('absensi_tanggal')
        ->label('Tanggal Kunjungan')
        ->dateTime(),
        Tables\Columns\TextColumn::make('absensi_keterangan')
        ->label('Keterangan')
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
      'index' => Pages\ListAbsensis::route('/'),
      'create' => Pages\CreateAbsensi::route('/create'),
      'edit' => Pages\EditAbsensi::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
