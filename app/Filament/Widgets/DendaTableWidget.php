<?php

namespace App\Filament\Widgets;

use App\Models\Fine;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class DendaTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Denda';

    protected int|string|array $columnSpan = 'full'; // Full width

    protected function getTableQuery(): Builder|Relation|null
    {
        return Fine::query()->with('user');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user.name')
                ->label('Nama Siswa')
                ->searchable()
                ->sortable(),

            TextColumn::make('amount')
                ->label('Jumlah Denda (Rp)')
                ->money('IDR')
                ->sortable(),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'danger' => 'belum',
                    'success' => 'lunas',
                ])
                ->sortable(),

            TextColumn::make('paid_date')
                ->label('Tanggal Bayar')
                ->date('d M Y')
                ->sortable(),
        ];
    }
}
