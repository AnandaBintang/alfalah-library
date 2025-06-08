<?php

namespace App\Filament\Resources\LoanExtensionResource\Pages;

use App\Filament\Resources\LoanExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanExtension extends EditRecord
{
    protected static string $resource = LoanExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
