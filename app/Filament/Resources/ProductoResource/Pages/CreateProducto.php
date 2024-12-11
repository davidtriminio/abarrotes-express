<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use App\Traits\PermisoCrear;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProducto extends CreateRecord
{
    protected static string $resource = ProductoResource::class;
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
