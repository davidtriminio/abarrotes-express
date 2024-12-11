<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use App\Traits\PermisoCrear;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateOrden extends CreateRecord
{
    protected static string $resource = OrdenResource::class;
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
