<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Models\Orden;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrdenesTotales extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'ordenesTotalesChart';
    protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';

    /**
     * Widget Title
     *
     * @var string|null
     */

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getHeading(): ?string
    {
        return 'Ordenes por mes (' . Carbon::now()->year . ')';
    }

    protected function getOptions(): array
    {
        $mesInicial = Carbon::now()->startOfYear();
        $mesActual = Carbon::now()->endOfMonth();

        $pedidos = Orden::selectRaw('MONTH(created_at) as mes, Year(created_at) as anio, count(*) as total')
            ->whereBetween('created_at', [$mesInicial, $mesActual])
            ->groupBy('mes', 'anio')
            ->orderBy('anio', 'desc')
            ->get()
            ->pluck('total', 'mes')
            ->toArray();

        $data = [];
        for ($i = 1; $i <= Carbon::now()->month; $i++) {
            $data[] = $pedidos[$i] ?? 0;
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 200,
            ],
            'series' => [
                [
                    'name' => 'Ordenes',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => array_slice(['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], 0, Carbon::now()->month),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['rgba(59, 130, 246, 1)'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 0.8,
                'colors' => ['#f1f1f1'],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'light',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.2,
                    'opacityFrom' => 0.8,
                    'opacityTo' => 0.4,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }
}
