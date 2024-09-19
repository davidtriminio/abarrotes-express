<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Models\Orden;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class EstadisticasOrdenes extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevas Ordenes', Orden::query()->where('estado_entrega', 'nuevo')->count())
                ->icon('heroicon-s-sparkles')
                ->label(new HtmlString('<span style="color: #d59a17;">Nuevas Ordenes</span>'))
                ->value(new HtmlString('<span style="color: #d59a17;">' . Orden::query()->where('estado_entrega', 'nuevo')->count() . ' Ordenes' . '</span>'))
                ->extraAttributes(['style' => 'border: 2px solid #d59a17; padding: 10px; border-radius: 20px;']),
            Stat::make('Ordenes En Proceso', Orden::query()->where('estado_entrega', 'procesado')->count())
                ->icon('heroicon-s-arrow-path')
                ->label(new HtmlString('<span style="color: #436cc3;">Ordenes En Proceso</span>'))
                ->value(new HtmlString('<span style="color: #436cc3;">' . Orden::query()->where('estado_entrega', 'procesado')->count() . ' Ordenes' . '</span>'))
                ->extraAttributes(['style' => 'border: 2px solid #436cc3; padding: 10px; border-radius: 20px;']),
            Stat::make('Ordenes Enviadas', Orden::query()->where('estado_entrega', 'enviado')->count())
                ->icon('heroicon-m-truck')
                ->label(new HtmlString('<span style="color: #43c372;">Ordenes Enviadas</span>'))
                ->value(new HtmlString('<span style="color: #43c372;">' . Orden::query()->where('estado_entrega', 'enviado')->count() . ' Ordenes' . '</span>'))
                ->extraAttributes(['style' => 'border: 2px solid #43c372; padding: 10px; border-radius: 20px;']),
        ];
    }
}
