<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Resources\DendaResource\Pages;
use App\Models\Fine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Components\Section;

class DendaResource extends Resource
{
  protected static ?string $model = Fine::class;

  protected static ?string $navigationLabel = 'Denda';

  protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('user_id')
          ->relationship('user', 'name')
          ->label('Pengguna')
          ->searchable()
          ->required(),

        Forms\Components\Select::make('loan_id')
          ->relationship(
            name: 'loan',
            titleAttribute: 'id'
          )
          ->getOptionLabelFromRecordUsing(fn($record) => $record->book->title ?? 'Tidak ada buku')
          ->searchable(),


        Forms\Components\TextInput::make('amount')
          ->label('Jumlah Denda')
          ->numeric()
          ->required(),

        Forms\Components\Select::make('status')
          ->label('Status')
          ->options([
            'unpaid' => 'Belum Dibayar',
            'paid' => 'Sudah Dibayar',
          ])
          ->required(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('user.name')
          ->searchable()
          ->label('Nama Siswa')
          ->sortable(),
        Tables\Columns\TextColumn::make('user.email')
          ->searchable()
          ->label('Email Siswa')
          ->sortable(),
        Tables\Columns\TextColumn::make('amount')
          ->label('Total')
        ,
        Tables\Columns\TextColumn::make('loan.book.name')
          ->label('Judul Buku')
        ,
        Tables\Columns\SelectColumn::make('status')
          ->label('Status')
          ->options([
            'unpaid' => 'Belum Bayar',
            'paid' => 'Sudah Bayar',
          ])
          ->updateStateUsing(function ($state, $record) {
            $record->update(['status' => $state]);
            \Filament\Notifications\Notification::make()
              ->title('Berhasil')
              ->body("Status berhasil diperbarui menjadi {$state}.")
              ->success()
              ->send();

            return $state;
          })
        ,

      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\ViewAction::make('Detail')
          ->label('Lihat Detail')
          ->infolist([
            Section::make('Detail Siswa')
              ->schema([
                TextEntry::make('user.name')->label('Nama Siswa'),
                TextEntry::make('user.email')->label('Email Siswa'),
                TextEntry::make('user.profile.class')->label('Kelas'),
                TextEntry::make('user.profile.phone')->label('No Telp Siswa'),
                TextEntry::make('user.profile.address')->label('Alamat Siswa'),
                TextEntry::make('user.profile.nis')
                  ->label('NIS')
                  ->visible(fn($record) => !empty($record->user->profile->nis)),
                TextEntry::make('user.profile.nisn')
                  ->label('NISN')
                  ->visible(fn($record) => !empty($record->user->profile->nisn)),
              ]),
            Section::make('Detail Peminjaman')
              ->schema([
                TextEntry::make('loan.book.title')->label('Judul Buku'),
                TextEntry::make('amount')->label('Total'),
                TextEntry::make('status')->label('Status'),
                TextEntry::make('loan.loan_date')->label('Tanggal Pinjam'),
                TextEntry::make('loan.return_date')->label('Tanggal Kembali'),
              ]),
          ]),
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

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Peminjaman';
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListDendas::route('/'),
      'create' => Pages\CreateDenda::route('/create'),
      'edit' => Pages\EditDenda::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
