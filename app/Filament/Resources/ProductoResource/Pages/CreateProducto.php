<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProducto extends CreateRecord
{
    protected static string $resource = ProductoResource::class;
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
