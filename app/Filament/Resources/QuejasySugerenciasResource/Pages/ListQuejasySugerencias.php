<?php

namespace App\Filament\Resources\QuejasySugerenciasResource\Pages;

use App\Filament\Resources\QuejasySugerenciasResource;
use App\Traits\PermisoVer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListQuejasySugerencias extends ListRecords
{
    protected static string $resource = QuejasySugerenciasResource::class;
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected ?string $heading = '';
    protected static ?string $title = 'Quejas y Sugerencias';
    protected static ?string $slug = 'quejas_sugerencias';
    use PermisoVer;

}
