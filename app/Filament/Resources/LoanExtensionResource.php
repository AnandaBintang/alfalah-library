<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\LoanExtensionResource\Pages;
use App\Models\LoanExtension;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LoanExtensionResource extends Resource
{
  protected static ?string $model = LoanExtension::class;

  protected static ?string $navigationLabel = 'Permintaan Perpanjangan';

  protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        //
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('loan.user.name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('previous_due_date')
          ->dateTime('d M Y'),
        Tables\Columns\TextColumn::make('new_due_date')
          ->dateTime('d M Y'),
        Tables\Columns\SelectColumn::make('status')
          ->options(
            collect(\App\Enum\ApprovalStatusEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->afterStateUpdated(function ($state, \App\Models\LoanExtension $record) {
            if ($state === \App\Enum\ApprovalStatusEnum::APPROVED->value) {
              $record->loan->update([
                'status' => \App\Enum\StatusLoanBookEnum::BORROWED->value,
                'due_date' => $record->new_due_date,
              ]);
              Notification::make()
                ->title('Success')
                ->success()
                ->body('Buku berhasil diperpanjang.')
                ->seconds(5)
                ->send();
            }
          }),

      ])
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->label('Status Peminjaman')
          ->options(
            collect(\App\Enum\ApprovalStatusEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->default(null)
          ->attribute('status')
          ->searchable(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
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

  public static function canCreate(): bool
  {
    return false;
  }

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Peminjaman';
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListLoanExtensions::route('/'),
      'create' => Pages\CreateLoanExtension::route('/create'),
      'edit' => Pages\EditLoanExtension::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
