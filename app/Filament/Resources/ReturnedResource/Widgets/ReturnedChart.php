<?php

namespace App\Filament\Resources\ReturnedResource\Widgets;

use App\Enum\StatusLoanBookEnum;
use App\Models\Loan;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Str;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReturnedChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'returnedChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'Aktivitas Pengembalian Buku';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
      $interval = $this->filterFormData['interval'] ?? 'day';

      $data = Trend::query(
        Loan::where('loan_status', StatusLoanBookEnum::RETURNED->value)
      )
        ->between(
          start: Carbon::parse($this->filterFormData['date_start']),
          end: Carbon::parse($this->filterFormData['date_end']),
        )
        ->{Str::camel('per_' . $interval)}()
        ->count();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Pengembalian',
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
            ->default('day')
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
