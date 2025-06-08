<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Widgets\ChartWidget;

class TopBooksChartWidget extends ChartWidget
{
    protected static ?string $heading = '5 Teratas Buku Dipinjam';

    protected static ?int $sort = 1; // Untuk posisi di dashboard

    protected function getData(): array
    {
        // Ambil data top 5 buku dipinjam
        $topBooks = Loan::selectRaw('book_id, COUNT(*) as total')
            ->with('book')
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $labels = $topBooks->pluck('book.title')->toArray();
        $data = $topBooks->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Dipinjam',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3B82F6',
                        '#60A5FA',
                        '#93C5FD',
                        '#BFDBFE',
                        '#1E40AF',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diubah ke 'horizontalBar' jika mau horizontal
    }
}
