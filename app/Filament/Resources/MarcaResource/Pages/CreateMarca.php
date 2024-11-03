<?php

namespace App\Filament\Resources\MarcaResource\Pages;

use App\Filament\Resources\MarcaResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateMarca extends CreateRecord
{
    protected static string $resource = MarcaResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.crear-registro';
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

