<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class TopVisitors extends Widget
{
  protected static string $view = 'filament.widgets.top-visitors';

  protected int | string | array $columnSpan = 1;

  protected function getViewData(): array
  {
    $topVisitors = User::select('users.id', 'users.name')
      ->whereHas('roles', function ($query) {
        $query->where('name', 'siswa');
      })
      ->withCount(['visitLogs as visits_count' => function ($query) {
        $query->whereNull('deleted_at');
      }])
      ->orderByDesc('visits_count')
      ->limit(5)
      ->get();

    return [
      'topVisitors' => $topVisitors,
    ];
  }
}
