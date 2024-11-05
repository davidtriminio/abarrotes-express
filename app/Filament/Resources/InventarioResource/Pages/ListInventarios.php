<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrdenResource\Widgets\EstadisticasOrdenes;
use App\Filament\Resources\OrdenResource\Widgets\OrdenesTotales;
use App\Filament\Resources\OrdenResource\Widgets\ProductosMasVendidos;
use App\Filament\Resources\OrdenResource\Widgets\UltimasOrdenes;
use App\Filament\Widgets\EstadisticasVentas;
use App\Filament\Widgets\InventarioWidget;
use App\Filament\Widgets\ProductoConCantidadBaja;
use App\Filament\Widgets\UsuarioConMasCompras;


class ListInventarios extends ListRecords
{
    protected static string $resource = InventarioResource::class;
    protected static ?string $title = 'Inventario Avanzado';
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.lista_personalizada';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            InventarioWidget::class,
            ProductoConCantidadBaja::class,
            UsuarioConMasCompras::class,
        ];
    }
}
