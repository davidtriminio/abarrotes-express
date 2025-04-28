<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use App\Traits\PermisoCrear;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Activitylog\Models\Activity;

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

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
