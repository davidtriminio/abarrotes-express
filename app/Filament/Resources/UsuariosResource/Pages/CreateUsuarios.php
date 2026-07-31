<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use App\Traits\PermisoCrear;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUsuarios extends CreateRecord
{
    protected static string $resource = UsuarioResource::class;
    protected static ?string $title = 'Crear Usuario';
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.crear-registro';
    use PermisoCrear;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
