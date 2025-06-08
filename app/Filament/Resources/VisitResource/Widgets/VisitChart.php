<?php

namespace App\Filament\Resources\VisitResource\Widgets;

use App\Models\VisitLog;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Str;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VisitChart extends ApexChartWidget
{
    protected static ?string $pollingInterval = '10s';

    /**
     * Chart Id
     */
    protected static ?string $chartId = 'visitChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'Aktivitas Kunjungan Perpustakaan';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
      $interval = $this->filterFormData['interval'] ?? 'month';

      $query = Trend::model(VisitLog::class)
        ->between(
          start: Carbon::parse($this->filterFormData['date_start']),
          end: Carbon::parse($this->filterFormData['date_end']),
        );

      // Dynamically call perDay(), perWeek(), or perMonth()
      $data = $query->{Str::camel('per_' . $interval)}()->count();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Kunjungan',
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
