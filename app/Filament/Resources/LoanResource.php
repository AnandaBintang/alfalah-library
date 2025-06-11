<?php

namespace App\Filament\Resources;

use App\Enum\ConfirmationStatusLoanEnum;
use App\Enum\RoleEnum;
use App\Enum\TimelineStatusEnum;
use App\Filament\Resources\LoanResource\Pages;
use App\Models\Fine;
use App\Models\Loan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LoanResource extends Resource
{
  protected static ?string $model = Loan::class;

  protected static ?string $navigationIcon = 'heroicon-o-book-open';

  protected static ?string $navigationLabel = 'Peminjaman Buku';

  protected static ?string $pollingInterval = '5s';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Select::make('user_id')
          ->label('Peminjam')
          ->relationship(
            name: 'user',
            titleAttribute: 'name',
            modifyQueryUsing: fn($query) => $query->role(RoleEnum::SISWA->value)
          )
          ->searchable()
          ->preload()
          ->required(),

        Select::make('book_id')
          ->label('Buku')
          ->relationship('book', 'title')
          ->searchable()
          ->preload()
          ->required(),

        DatePicker::make('loan_date')
          ->label('Tanggal Peminjaman')
          ->required(),

        DatePicker::make('due_date')
          ->label('Tanggal Jatuh Tempo')
          ->required(),

        Select::make('loan_status')
          ->required()
          ->label('Status')
          ->options(
            collect(\App\Enum\StatusLoanBookEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          ),

        Select::make('confirmation_status')
          ->required()
          ->label('Status')
          ->options(
            collect(\App\Enum\ConfirmationStatusLoanEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          ),

        Select::make('timeline_status')
          ->required()
          ->label('Status')
          ->options(
            collect(\App\Enum\TimelineStatusEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          ),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('user.name')
          ->label('Nama Peminjam')
          ->searchable(),

        Tables\Columns\TextColumn::make('book.title')
          ->label('Nama Buku'),

        Tables\Columns\TextColumn::make('loan_date')
          ->label('Tanggal Peminjaman')
          ->dateTime('d M Y'),

        Tables\Columns\TextColumn::make('due_date')
          ->label('Tanggal Jatuh Tempo')
          ->dateTime('d M Y'),

        Tables\Columns\TextColumn::make('return_date')
          ->label('Tanggal Pengembalian')
          ->dateTime('d M Y'),

        Tables\Columns\TextColumn::make('timeline_status')
          ->label('Status pengembalian'),

        Tables\Columns\SelectColumn::make('loan_status')
          ->label('Status Buku')
          ->options(
            collect(\App\Enum\StatusLoanBookEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->afterStateUpdated(function ($state, $record) {
            if ($state === \App\Enum\StatusLoanBookEnum::RETURNED->value) {
              $now = now();

              // Update return_date
              $record->update([
                'return_date' => $now,
                'timeline_status' => TimelineStatusEnum::ONTIME->value,
              ]);

              // Tambah stok buku
              if ($record->book) {
                $record->book->increment('stock');
              }

              // Update semua cart item dengan cart_id milik user & book_id ini
              if ($record->user && $record->user->cart) {
                \App\Models\CartItem::where('cart_id', $record->user->cart->id)
                  ->where('book_id', $record->book_id)
                  ->update([
                    'status' => ConfirmationStatusLoanEnum::APPROVED->value,
                  ]);
              }

              // Hitung denda jika terlambat
              if ($record->due_date < $now) {
                $record->update([
                  'timeline_status' => TimelineStatusEnum::OVERDUE->value,
                ]);

                $minutesLate = $record->due_date->diffInMinutes($now);
                $daysLateDecimal = $minutesLate / 1440;

                $daysLate = $daysLateDecimal > 0.5 ? ceil($daysLateDecimal) : floor($daysLateDecimal);

                $finePerDay = 1000;
                $totalFine = floor(($daysLate * $finePerDay) / 100) * 100;

                Fine::create([
                  'loan_id' => $record->id,
                  'user_id' => $record->user_id,
                  'description' => 'Terlambat mengembalikan buku ' . $record->book->title . ' selama ' . $daysLate . ' hari.',
                  'amount' => $totalFine,
                ]);

                Notification::make()
                  ->title('Terlambat mengembalikan buku ' . $record->book->title)
                  ->success()
                  ->body('Terlambat mengembalikan buku ' . $record->book->title . ' selama ' . $daysLate . ' hari dengan denda ' . $totalFine)
                  ->seconds(15)
                  ->send();
              }

              Notification::make()
                ->title('Buku berhasil dikembalikan.')
                ->success()
                ->body('User tidak mempunyai denda')
                ->seconds(15)
                ->send();
            }
          }),

        Tables\Columns\SelectColumn::make('confirmation_status')
          ->label('Status Admin')
          ->options(
            collect(\App\Enum\ConfirmationStatusLoanEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->afterStateUpdated(function ($state, $record) {
            if ($state == \App\Enum\ConfirmationStatusLoanEnum::APPROVED->value) {
              $record->update([
                'loan_status' => \App\Enum\StatusLoanBookEnum::BORROWED->value,
              ]);
            } else {
              $record->update([
                'loan_status' => \App\Enum\StatusLoanBookEnum::PENDING->value,
              ]);
            }
          })
          ->placeholder(false),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('loan_status')
          ->label('Status Peminjaman')
          ->options(
            collect(\App\Enum\StatusLoanBookEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->default(null)
          ->attribute('status')
          ->searchable(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
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
      'index' => Pages\ListLoans::route('/'),
      'create' => Pages\CreateLoan::route('/create'),
      'edit' => Pages\EditLoan::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
