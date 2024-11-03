<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateRol extends CreateRecord
{
    protected static string $resource = RolResource::class;
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
            CreateAction::make('Crear')
                ->label('Crear Rol')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
