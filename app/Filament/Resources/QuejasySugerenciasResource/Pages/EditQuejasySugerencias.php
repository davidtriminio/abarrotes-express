<?php

namespace App\Filament\Resources\QuejasySugerenciasResource\Pages;

use App\Filament\Resources\QuejasySugerenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuejasySugerencias extends EditRecord
{
    protected static string $resource = QuejasySugerenciasResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    protected static ?string $title = 'Detalles de la Queja/Sugerencia';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('borrar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->icon('heroicon-o-trash'),
        ];
    }
}
