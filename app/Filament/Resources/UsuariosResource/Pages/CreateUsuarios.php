<?php

namespace App\Filament\Resources\UsuariosResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUsuarios extends CreateRecord
{
    protected static string $resource = UsuarioResource::class;
    protected static ?string $title = 'Crear Usuario';
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.crear-record';
    public static function canAccess(array $parameters = []): bool
    {
        $slug = self::getResource()::getSlug();
        $usuario = auth()->user();
        if ($usuario->hasPermissionTo('crear:' . $slug)) {
            return true;
        } else {
            return false;
        }
    }

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
