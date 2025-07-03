<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';

  protected static ?string $navigationLabel = 'User';

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen User';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->label('Nama')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('email')
          ->label('Email')
          ->email()
          ->required()
          ->unique(User::class, 'email', ignoreRecord: true)
          ->maxLength(255),
        Forms\Components\TextInput::make('password')
          ->label('Password')
          ->password()
          ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
          ->minLength(8)
          ->maxLength(255)
          ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
          ->dehydrated(fn($state) => !is_null($state)),
        Forms\Components\Select::make('role')
          ->label('Role')
          ->options(Role::pluck('name', 'name')->toArray())
          ->required(),
        Forms\Components\Toggle::make('is_active')
          ->label('Aktif')
          ->default(true),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->modifyQueryUsing(function (Builder $query) {
        return $query
          ->leftJoin('model_has_roles', function ($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
              ->where('model_has_roles.model_type', '=', \App\Models\User::class);
          })
          ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
          ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id') // Tambahkan join ke profiles
          ->select([
            'users.*',
            'roles.name as role_name',
            'profiles.nis as profile_nis',
            'profiles.nisn as profile_nisn',
            'profiles.class as profile_class',
            'profiles.address as profile_address',
            'profiles.phone as profile_phone',
          ]);
      })
      ->columns([
        Tables\Columns\TextColumn::make('name')->label('Nama')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('email')->label('Email')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('role_name')->label('Role')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('profile_nis')
          ->label('NIS')
          ->getStateUsing(fn($record) => $record->profile_nis ?? '-'),
        Tables\Columns\TextColumn::make('profile_nisn')
          ->label('NISN')
          ->getStateUsing(fn($record) => $record->profile_nisn ?? '-'),
        Tables\Columns\TextColumn::make('profile_class')
          ->label('Kelas')
          ->getStateUsing(fn($record) => $record->profile_class ?? '-'),
        Tables\Columns\TextColumn::make('profile_address')
          ->label('Alamat')
          ->getStateUsing(fn($record) => $record->profile_address ?? '-'),
        Tables\Columns\TextColumn::make('profile_phone')
          ->label('No HP')
          ->getStateUsing(fn($record) => $record->profile_phone ?? '-'),
        Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean()->sortable(),
      ])
      ->filters([
        // SelectFilter::make('role_name')
        //   ->label('Role')
        //   ->options(
        //     Role::pluck('name', 'name')->toArray()
        //   )
        //   ->query(function (Builder $query, $state) {
        //     if ($state) {
        //       $query->where('roles.name', $state);
        //     }
        //   }),
      ])
      ->headerActions([
        ExportAction::make()
          ->label('Export Semua User'),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        ExportBulkAction::make()
          ->label('Export yang Dipilih'),
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
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && Auth::user()->hasRole(RoleEnum::ADMIN->value);
  }

  public static function getExportColumns(): array
  {
    return [
      'name' => 'Nama',
      'email' => 'Email',
      'role_name' => 'Role',
      'is_active' => 'Aktif',
      'profile_nis' => 'NIS',
      'profile_nisn' => 'NISN',
      'profile_class' => 'Kelas',
      'profile_address' => 'Alamat',
      'profile_phone' => 'No HP',
    ];
  }
}
