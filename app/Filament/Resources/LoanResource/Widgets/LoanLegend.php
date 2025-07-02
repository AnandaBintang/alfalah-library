<?php

namespace App\Filament\Resources\LoanResource\Widgets;

use Filament\Widgets\Widget;

class LoanLegend extends Widget
{
  protected static string $view = 'filament.resources.loan-resource.widgets.loan-legend';
  protected static ?string $heading = 'Keterangan Status';
}
