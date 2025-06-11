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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
      ->columns([
        Tables\Columns\TextColumn::make('name')->label('Nama')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('email')->label('Email')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('role')
          ->label('Role')
          ->getStateUsing(fn(User $record) => $record->getRoleNames()->first() ?? '-')
          ->sortable(),
        Tables\Columns\IconColumn::make('is_active')
          ->label('Aktif')
          ->boolean()
          ->sortable(),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\ExportBulkAction::make(),
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
}
