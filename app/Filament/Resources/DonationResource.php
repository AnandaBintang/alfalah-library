<?php

namespace App\Filament\Resources;

use App\Enum\ApprovalStatusEnum;
use App\Enum\RoleEnum;
use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

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
          ->poll('10s')
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('donation_date')
                    ->dateTime('d M Y'),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        ApprovalStatusEnum::PENDING->value => 'Pending',
                        ApprovalStatusEnum::APPROVED->value => 'Disetujui',
                        ApprovalStatusEnum::REJECTED->value => 'Ditolak',
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state === ApprovalStatusEnum::APPROVED->value) {
                            \App\Models\Book::updateOrCreate(
                                ['title' => $record->item_name],
                                [
                                    'subtitle' => $record->description,
                                    'stock' => \DB::raw('quantity + '.$record->quantity),
                                ]
                            );

                            Notification::make()
                                ->title('Success')
                                ->success()
                                ->body('Buku berhasil disimpan.')
                                ->seconds(5)
                                ->send();
                        }
                    })
                    ->placeholder(false),

            ])
            ->filters([
            Tables\Filters\SelectFilter::make('status')
                    ->label('Filtet by status')
                    ->options(
                        collect(\App\Enum\ApprovalStatusEnum::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => ucfirst($status->name)])
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
