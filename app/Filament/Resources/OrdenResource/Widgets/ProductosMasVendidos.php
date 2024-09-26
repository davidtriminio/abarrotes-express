<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Models\Producto;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductosMasVendidos extends ApexChartWidget
{
    protected static ?string $chartId = 'productosMasVendidos';
    protected static ?string $heading = 'Productos Más Vendidos';
    protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';

    protected function getOptions(): array
    {
        $productos = Producto::selectRaw('productos.nombre, SUM(elementos_ordenes.cantidad) as total_vendido')
            ->join('elementos_ordenes', 'productos.id', '=', 'elementos_ordenes.producto_id')
            ->groupBy('productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $nombres = $productos->pluck('nombre')->toArray();
        $ventas = $productos->pluck('total_vendido')->toArray();

        $colores = ['#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12', '#d35400', '#c0392b', '#8e44ad', '#2980b9'];

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 200,
            ],
            'series' => [
                [
                    'name' => 'Cantidad Vendida',
                    'data' => $ventas,
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '50%',
                    'distributed' => true,
                ],
            ],
            'xaxis' => [
                'categories' => $nombres,
                'labels' => [
                    'show' => false,
                    'style' => [
                        'colors' => $colores,
                        'fontSize' => '12px',
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => $colores,
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'color' => '#f1f1f1',
                    'fontSize' => '12px',
                    'fontFamily' => 'inherit',
                ],
            ],
            'legend' => [
                'show' => true,
                'position' => 'bottom',
                'horizontalAlign' => 'center',
                'fontSize' => '14px',
                'fontFamily' => 'inherit',
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'opacityFrom' => 1,
                    'opacityTo' => 0.9,
                    'shade' => 'light',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                ],
            ],
            'stroke' => [
                'width' => 0.8,
                'curve' => 'straight',
                'colors' => ['#f1f1f1'],
            ],
        ];
    }
}
