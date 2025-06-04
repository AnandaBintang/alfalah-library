<?php

namespace App\Filament\Widgets;

use App\Enum\RoleEnum;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::role(RoleEnum::SISWA->value)->count())
                ->description('Jumlah seluruh pengguna')
                ->icon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
