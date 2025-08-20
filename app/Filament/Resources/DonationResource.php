<?php

namespace App\Filament\Resources;

use App\Enum\ApprovalStatusEnum;
use App\Enum\RoleEnum;
use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use App\Notifications\DonationNotification;
use App\Notifications\LoanNotification;
use App\Notifications\StatusNotification;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Text;

class DonationResource extends Resource
{
  protected static ?string $model = Donation::class;

  protected static ?string $navigationIcon = 'heroicon-o-book-open';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('user.name')
          ->label('Nama Donatur'),
        TextInput::make('item_name')
          ->label('Nama Buku'),
        TextInput::make('quantity')
          ->label('Jumlah'),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->poll('10s')
      ->columns([
        Tables\Columns\ImageColumn::make('image')
          ->openUrlInNewTab()
          ->label('Gambar'),
        Tables\Columns\TextColumn::make('user.name')
          ->searchable()
          ->label('Nama Donatur'),
        Tables\Columns\TextColumn::make('item_name')
          ->label('Nama Buku'),
        Tables\Columns\TextColumn::make('description')
          ->label('Deskripsi'),
        Tables\Columns\TextColumn::make('quantity')
          ->label('Total Donasi Buku'),
        Tables\Columns\TextColumn::make('donation_date')
          ->dateTime('d M Y')
          ->label('Tanggal Donasi Masuk'),
        Tables\Columns\SelectColumn::make('status')
          ->label('Status')
          ->options([
            ApprovalStatusEnum::PENDING->value => 'Pending',
            ApprovalStatusEnum::APPROVED->value => 'Disetujui',
            ApprovalStatusEnum::REJECTED->value => 'Ditolak',
          ])
          ->afterStateUpdated(function ($record, $state) {
            DB::beginTransaction();
            $user = $record->user;


            if ($state === ApprovalStatusEnum::APPROVED->value) {
              $quantity = $record->quantity;

              $book = \App\Models\Book::updateOrCreate(
                ['title' => $record->item_name],
                [
                  'subtitle' => $record->description,

                ]
              );

              $book->increment('stock', $quantity);

              Notification::make()
                ->title('Success')
                ->success()
                ->body('Buku berhasil disimpan.')
                ->seconds(5)
                ->send();

              $user->notify(new StatusNotification('success', "Donasi disetujui."));
              DB::commit();
            } elseif ($state === ApprovalStatusEnum::REJECTED->value) {
              Notification::make()
                ->title('Success')
                ->success()
                ->body('Buku ditolak.')
                ->seconds(5)
                ->send();

              $user->notify(new StatusNotification('error', "Donasi ditolak."));
              DB::rollBack();
            }
            DB::rollBack();
          })
          ->placeholder(false),

      ])
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->label('Filtet by status')
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
        Tables\Actions\ViewAction::make('Detail Donatur')
          ->infolist([
            Section::make('Detail Item')
              ->schema([
                ImageEntry::make('image'),
                TextEntry::make('item_name')->label('Nama Buku'),
                TextEntry::make('description')->label('Deskripsi'),
                TextEntry::make('quantity')->label('Jumlah'),
                TextEntry::make('donation_date')->label('Tanggal Donasi Masuk'),
              ]),

            Section::make('Detail Donatur')
              ->schema([
                TextEntry::make('user.name')->label('Nama Donatur'),
                TextEntry::make('user.email')->label('Email Donatur'),
                TextEntry::make('user.profile.phone')->label('No. Telp Donatur'),
                TextEntry::make('user.profile.class')->label('Kelas'),
                TextEntry::make('user.profile.level')->label('Level'),

                // Hanya tampil kalau ada NIS
                TextEntry::make('user.profile.nis')
                  ->label('NIS')
                  ->visible(fn($record) => !empty($record->user->profile->nis)),

                // Hanya tampil kalau ada NISN
                TextEntry::make('user.profile.nisn')
                  ->label('NISN')
                  ->visible(fn($record) => !empty($record->user->profile->nisn)),
              ]),
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
      'index' => Pages\ListDonations::route('/'),
      'create' => Pages\CreateDonation::route('/create'),
      'edit' => Pages\EditDonation::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
