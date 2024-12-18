<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use App\Traits\PermisoEditar;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action as ActionNotification;
use Spatie\Activitylog\Models\Activity;

class EditOrden extends EditRecord
{
    protected static string $resource = OrdenResource::class;
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

    protected function beforeSave(): void
    {
        // Desactiva temporalmente la creación de eventos automáticos
        Activity::withoutEvents(function () {
            // Registrar el evento manualmente
            activity()
                ->event('Actualización')
                ->performedOn($this->record)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $this->record->getChanges()])
                ->log('Orden #' . $this->record->id . ' actualizada por ' . auth()->user()->name);
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
