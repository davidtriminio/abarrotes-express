<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use App\Traits\PermisoEditar;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRol extends EditRecord
{
    protected static string $resource = RolResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    use PermisoEditar;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            DeleteAction::make('Borrar')
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
