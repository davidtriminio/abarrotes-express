<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Models\Orden;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstadisticasOrdenes extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevas Ordenes', Orden::query()->where('estado_entrega', 'nuevo')->count())
                ->icon('heroicon-s-sparkles'),
            Stat::make('Ordenes En Proceso', Orden::query()->where('estado_entrega', 'en_proceso')->count())
                ->icon('heroicon-s-arrow-path'),
            Stat::make('Ordenes Enviadas', Orden::query()->where('estado_entrega', 'enviado')->count())
                ->icon('heroicon-m-truck'),
        ];
    }
}
