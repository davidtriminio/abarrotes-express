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
        $subTotal = 0;
        $descuentoTotal = 0;

        foreach ($this->data['elementos'] as $elemento) {
            $producto = \App\Models\Producto::find($elemento['producto_id']);

            if ($producto) {
                $precioOriginal = $producto->precio;
                $cantidad = $elemento['cantidad'] ?? 1;
                $precioConDescuento = $producto->en_oferta
                    ? $precioOriginal - ($precioOriginal * ($producto->porcentaje_oferta / 100))
                    : $precioOriginal;

                $subTotal += $precioOriginal * $cantidad;

                if ($producto->en_oferta) {
                    $descuentoTotal += ($precioOriginal - $precioConDescuento) * $cantidad;
                }
            }
        }

        $this->record->sub_total = $subTotal;
        $this->record->descuento_total = $descuentoTotal;
        $this->record->total_final = $subTotal - $descuentoTotal;

        // Luego tu log de actividad
        Activity::withoutEvents(function () {
            activity()
                ->event('Actualización')
                ->performedOn($this->record)
                ->causedBy(auth()->user())
                ->withProperties(['attributes' => $this->record->getChanges()])
                ->log('Orden #' . $this->record->id . ' actualizada por ' . auth()->user()->name);
        });
    }

}
