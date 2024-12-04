<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\OrdenResource\Widgets\EstadisticasOrdenes;
use App\Filament\Resources\OrdenResource\Widgets\OrdenesTotales;
use App\Filament\Resources\OrdenResource\Widgets\ProductosMasVendidos;
use App\Filament\Resources\OrdenResource\Widgets\UltimasOrdenes;
use App\Filament\Widgets\EstadisticasVentas;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

use App\Filament\Widgets\ProductosQueVanAVencer;
use App\Filament\Widgets\ProductoConCantidadBaja;
use App\Filament\Widgets\UsuarioConMasCompras;
use App\Filament\Widgets\ProductosConMasCompradas;

class InventarioAvanzado extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $modelLabel = 'Inventario Avanzado';
    protected static ?string $navigationLabel = 'Inventario Avanzado';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.inventario-avanzado';



    public function getWidgets(): array
    {
        return[
            ProductosQueVanAVencer::class,
            ProductoConCantidadBaja::class,
            UsuarioConMasCompras::class,
            ProductosConMasCompradas::class,
        ];
    }

    public function getVisibleWidgets(): array
    {
        // Aquí puedes definir la lógica para determinar qué widgets son visibles
        return $this->getWidgets(); // Por defecto, devuelve todos los widgets
    }

    
    public function getTitle(): string|Htmlable
    {
        return 'Inventarios Avanzados';
    }
}
