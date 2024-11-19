<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
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

    /*Notification::make('Cambios guardados correctamente.')
           ->success()
           ->title('Orden ' . $this->record->id . ' actualizada.')
           ->body('La orden ' . $this->record->id . ' cambio ha estado ' . $this->record->estado_entrega . '.')
           ->icon('heroicon-o-check-circle')
           ->iconColor('success')
           ->actions([
               ActionNotification::make('Ver')
                   ->label('Ver Orden')
                   ->url(OrdenResource::getUrl('view', ['record' => $this->record])),
           ])
           ->sendToDatabase($this->record->user); // Aquí se usa solo el usuario asociado*/

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
