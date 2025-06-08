<?php

namespace App\Filament\Resources\DonationResource\Widgets;

use App\Models\Donation;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Str;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class DonationChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'donationChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'Aktivitas Donasi ';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
      $interval = $this->filterFormData['interval'] ?? 'month';
      $start = $this->filterFormData['date_start'] ?? now()->subMonth()->startOfDay();
      $end = $this->filterFormData['date_end'] ?? now()->endOfDay();

      $start = Carbon::parse($start)->startOfDay();
      $end = Carbon::parse($end)->endOfDay();

      $method = Str::camel('per_' . $interval);

      $data = Trend::model(Donation::class)
        ->between(start: $start, end: $end)
        ->{$method}()
        ->count();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Donasi',
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
