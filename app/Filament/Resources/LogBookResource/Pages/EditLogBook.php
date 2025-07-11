<?php

namespace App\Filament\Resources\LogBookResource\Pages;

use App\Filament\Resources\LogBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogBook extends EditRecord
{
    protected static string $resource = LogBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
