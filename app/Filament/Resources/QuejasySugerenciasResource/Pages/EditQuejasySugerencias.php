<?php

namespace App\Filament\Resources\QuejasySugerenciasResource\Pages;

use App\Filament\Resources\QuejasySugerenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuejasySugerencias extends EditRecord
{
    protected static string $resource = QuejasySugerenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
