<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogBookResource\Pages;
use App\Filament\Resources\LogBookResource\RelationManagers;
use App\Models\KondisiBook;
use App\Models\LogBook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogBookResource extends Resource
{
    protected static ?string $model = KondisiBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  protected static ?string $navigationLabel = 'Log Kondisi Buku';

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen Buku';
  }

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
                Tables\Columns\TextColumn::make('book.title')
              ->label("Judul Buku"),
              Tables\Columns\TextColumn::make('status')
              ->label("Status"),
              Tables\Columns\TextColumn::make('notes')
              ->label("Catatan"),
              Tables\Columns\TextColumn::make('reported_at')
              ->label("Tanggal Laporan")
              ->dateTime()
            ])
            ->filters([
                //
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
            'index' => Pages\ListLogBooks::route('/'),
            'create' => Pages\CreateLogBook::route('/create'),
            'edit' => Pages\EditLogBook::route('/{record}/edit'),
        ];
    }
}
