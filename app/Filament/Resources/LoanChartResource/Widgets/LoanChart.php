<?php

namespace App\Filament\Resources\LoanChartResource\Widgets;

use App\Enum\StatusLoanBookEnum;
use App\Models\Loan;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Str;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class LoanChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'loanChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'Aktivitas Peminjaman Buku';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
        $interval = $this->filterFormData['interval'] ?? 'month';
        $start = $this->filterFormData['date_start'] ?? now()->subMonth();
        $end = $this->filterFormData['date_end'] ?? now();

        $method = Str::camel('per_'.$interval);

        $data = Trend::query(
            Loan::where('status', StatusLoanBookEnum::BORROWED->value)
        )
            ->between(
                start: Carbon::parse($start)->startOfDay(),
                end: Carbon::parse($end)->endOfDay(),
            )
            ->{$method}()
            ->count();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Peminjaman',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn (TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('interval')
                ->label('Tampilan Data')
                ->default('month')
                ->options([
                    'day' => 'Harian',
                    'week' => 'Mingguan',
                    'month' => 'Bulanan',
                ]),
            DatePicker::make('date_start')
                ->default(now()->subMonth()),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}
