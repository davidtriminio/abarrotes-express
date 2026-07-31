<?php

namespace Z3d0X\FilamentLogger\Resources\ActivityResource\Pages;

use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected ?string $heading = '';
    public static function getResource(): string
    {
        return config('filament-logger.activity_resource');
    }
}
