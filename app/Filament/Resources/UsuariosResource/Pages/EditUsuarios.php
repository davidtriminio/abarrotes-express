<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Cancel;

class EditUsuarios extends EditRecord
{
    protected static string $resource = UsuarioResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    protected static ?string $title = 'Editar Usuario';

    protected function getHeaderActions(): array
    {
        return [
            /*Botón para regresar a la lista de usuarios*/
            Action::make('Regresar')
                ->url(UsuarioResource::getUrl('view',['record' => $this->record->id]))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),

            DeleteAction::make('Borrar')
                ->visible(function () {
                    $usuarioActual = auth()->user();
                    $usuarioSeleccionado = $this->record;
                    if (auth()->user()->hasRole('SuperAdmin')) {
                        if ($usuarioSeleccionado->hasRole('SuperAdmin') === true){
                            return false;
                        } else
                            return true;
                    } else {
                        if ($usuarioActual->id === $usuarioSeleccionado->id || $usuarioSeleccionado->hasRole('Administrador') || $usuarioSeleccionado->hasRole('SuperAdmin')) {
                            return false;
                        }
                        return true;
                    }
                })
            ->icon('heroicon-o-trash'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
