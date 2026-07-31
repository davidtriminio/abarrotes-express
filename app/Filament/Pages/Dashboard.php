<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OrdenResource\Widgets\EstadisticasOrdenes;
use App\Filament\Resources\OrdenResource\Widgets\OrdenesTotales;
use App\Filament\Resources\OrdenResource\Widgets\ProductosMasVendidos;
use App\Filament\Resources\OrdenResource\Widgets\UltimasOrdenes;
use App\Filament\Widgets\EstadisticasVentas;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationLabel = 'Inicio';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $activeNavigationIcon = 'heroicon-s-home';
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return[
            EstadisticasOrdenes::class,
            EstadisticasVentas::class,
            OrdenesTotales::class,
            ProductosMasVendidos::class,
            UltimasOrdenes::class,
        ];
    }
    public function getTitle(): string|Htmlable
    {
        return '';
    }
}
