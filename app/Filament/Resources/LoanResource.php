<?php

namespace App\Filament\Resources;

use App\Enum\ConfirmationStatusLoanEnum;
use App\Enum\RoleEnum;
use App\Enum\TimelineStatusEnum;
use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\Widgets\LoanLegend;
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
use Illuminate\Support\Facades\DB;

class LoanResource extends Resource
{
  protected static ?string $model = Loan::class;

  protected static ?string $navigationIcon = 'heroicon-o-book-open';

  protected static ?string $navigationLabel = 'Peminjaman Buku';


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
      ->poll('10s')
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
          ->label('Status Pengembalian')
          ->formatStateUsing(fn($state) => strtoupper($state))
          ->badge()
          ->colors([
            'info' => 'PENDING',
            'success' => 'ONTIME',
            'danger' => 'OVERDUE',
          ]),

        Tables\Columns\SelectColumn::make('loan_status')
          ->label('Status Buku')
          ->options(
            collect(\App\Enum\StatusLoanBookEnum::cases())
              ->mapWithKeys(fn($status) => [$status->value => ucfirst($status->name)])
              ->toArray()
          )
          ->afterStateUpdated(function ($state, $record) {
            DB::beginTransaction();
            try {
              $now = now();

              if ($state === \App\Enum\StatusLoanBookEnum::BORROWED->value) {

                // Update timeline status to BORROWED
                $record->update([
                  'loan_status' => \App\Enum\StatusLoanBookEnum::BORROWED->value,
                  'timeline_status' => \App\Enum\TimelineStatusEnum::PENDING->value,
                  'confirmation_status' => ConfirmationStatusLoanEnum::APPROVED->value,
                  'return_date' => null
                ]);

                if ($record->book) {
                  $record->book->decrement('stock');
                }

                Notification::make()
                  ->title('Buku berhasil dipinjam.')
                  ->success()
                  ->body('Status peminjaman sekarang adalah BORROWED.')
                  ->seconds(15)
                  ->send();
              } elseif ($state === \App\Enum\StatusLoanBookEnum::PENDING->value) {
                // Update timeline status to PENDING
                $record->update([
                  'loan_status' => \App\Enum\StatusLoanBookEnum::PENDING->value,
                  'timeline_status' => \App\Enum\TimelineStatusEnum::PENDING->value,
                  'confirmation_status' => ConfirmationStatusLoanEnum::PENDING->value,
                  'return_date' => null
                ]);

                if ($record->book) {
                  $record->book->decrement('stock');
                }

                Notification::make()
                  ->title('Pinjaman menunggu persetujuan.')
                  ->warning()
                  ->body('Status peminjaman sekarang adalah PENDING.')
                  ->seconds(15)
                  ->send();
              } elseif ($state === \App\Enum\StatusLoanBookEnum::RETURNED->value) {
                // Update return_date and timeline status
                $record->update([
                  'return_date' => $now,
                  'loan_status' => \App\Enum\StatusLoanBookEnum::RETURNED->value,
                  'timeline_status' => $record->due_date < $now
                    ? \App\Enum\TimelineStatusEnum::OVERDUE->value
                    : \App\Enum\TimelineStatusEnum::ONTIME->value,
                ]);

                // Increment stock if book exists
                if ($record->book) {
                  $record->book->increment('stock');
                }

                // Update all cart items related to this book and user's carts
                if ($record->user && $record->user->cart) {
                  foreach ($record->user->cart as $cart) {
                    \App\Models\CartItem::where('cart_id', $cart->id)
                      ->where('book_id', $record->book_id)
                      ->update([
                        'status' => \App\Enum\StatusCartItemEnum::APPROVED->value,
                      ]);
                  }
                }

                // Handle overdue cases
                if ($record->due_date < $now) {
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
                    ->danger()
                    ->body('Terlambat mengembalikan buku selama ' . $daysLate . ' hari dengan denda sebesar ' . $totalFine . '.')
                    ->seconds(15)
                    ->send();
                } else {
                  Notification::make()
                    ->title('Buku berhasil dikembalikan.')
                    ->success()
                    ->body('Tidak ada denda untuk pengembalian ini.')
                    ->seconds(15)
                    ->send();
                }
              }

              DB::commit();
            } catch (\Exception $e) {
              DB::rollBack();
              throw $e;
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
          ->attribute('loan_status')
          ->searchable(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\BulkAction::make('approve_pending')
            ->label('Setujui yang Pending')
            ->action(function ($records) {
              foreach ($records as $record) {
                if ($record->confirmation_status == \App\Enum\ConfirmationStatusLoanEnum::PENDING->value) {
                  $record->update([
                    'confirmation_status' => \App\Enum\ConfirmationStatusLoanEnum::APPROVED->value,
                    'loan_status' => \App\Enum\StatusLoanBookEnum::BORROWED->value,
                  ]);
                }
              }
            })
            ->requiresConfirmation()
            ->color('success')
            ->icon('heroicon-o-check'),
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
