<?php

namespace App\Filament\Resources\DireccionResource\Pages;

use App\Filament\Resources\DireccionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDireccions extends ListRecords
{
    protected static string $resource = DireccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('Crear')
                ->label('Crear Dirección')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

}
