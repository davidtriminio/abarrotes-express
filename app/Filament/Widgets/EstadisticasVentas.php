<?php
namespace App\Filament\Widgets;
use App\Models\ElementoOrden;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
class EstadisticasVentas extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        $numeroFormateado = function (int $number): string {
            if ($number < 1000) {
                return (string)Number::format($number, 0) . '.00';
            }
            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . ' MIL';
            }
            return Number::format($number / 1000000, 2) . ' MILLONES';
        };
        /*Llamada a las funciones*/
        $estadisticasDiarias = $this->calcularEstadisticasDiarias();

        /*Definir valores predeterminados*/
        $ganancia_actual_dia = $estadisticasDiarias['ganancia_actual_dia'] ?? 0;
        $porcentaje_cambio_diario = $estadisticasDiarias['porcentaje_cambio_diario'] ?? 0;

        /*Definir los colores*/
        $color = $ganancia_actual_dia > 0
            ? ($porcentaje_cambio_diario > 0 ? 'success' : ($porcentaje_cambio_diario < 0 ? 'warning' : 'white'))
            : 'white';

        /*Mostrar las estadísticas*/
        return [
            Stat::make('Ganancias Diarias', 'LPS ' . $numeroFormateado($ganancia_actual_dia))
                ->descriptionIcon($porcentaje_cambio_diario > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down', IconPosition::Before)
                ->color($color)
                ->description(round($porcentaje_cambio_diario, 2) . '%' . ' de ganancias con respecto al día anterior')
                // Mostrar el chart con las ventas de los últimos 7 días
                ->chart($this->ventasUltimos7Dias()),
        ];
    }

    /*Calcular las estadísticas diarias y mostrar las ventas de los últimos 7 días en los charts*/
    protected function calcularEstadisticasDiarias(): array
    {
        // Cambié el cálculo de ganancias diarias para usar total_final en lugar de elementos
        $ganancia_actual_dia = \DB::table('ordenes')
            ->where('estado_entrega', 'entregado')
            ->whereDate('fecha_entrega', '=', Carbon::today())
            ->sum('total_final'); // Cambié a total_final

        // Obtener ganancias del día anterior
        $ganancia_dia_anterior = \DB::table('ordenes')
            ->where('estado_entrega', 'entregado')
            ->whereDate('fecha_entrega', '=', Carbon::yesterday())
            ->sum('total_final'); // Cambié a total_final

        // Cálculo del porcentaje de cambio diario
        $porcentaje_cambio_diario = $ganancia_dia_anterior > 0
            ? (($ganancia_actual_dia - $ganancia_dia_anterior) / $ganancia_dia_anterior) * 100
            : 0;

        return [
            'ganancia_actual_dia' => $ganancia_actual_dia,
            'ganancia_dia_anterior' => $ganancia_dia_anterior,
            'porcentaje_cambio_diario' => $porcentaje_cambio_diario,
        ];
    }
    protected function ventasUltimos7Dias(): array
    {
        $fechas = [];
        $ventas = [];

        // Generar un rango de fechas para los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->format('Y-m-d');
            $fechas[] = $fecha;
            $ventas[$fecha] = 0; // Inicializar cada fecha con 0
        }

        // Consultar las ventas
        $ventas_ultimos_7_dias = ElementoOrden::join('ordenes', 'elementos_ordenes.orden_id', '=', 'ordenes.id')
            ->where('ordenes.estado_entrega', 'entregado')
            ->whereDate('ordenes.fecha_entrega', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(ordenes.fecha_entrega) as fecha, SUM(ordenes.total_final) as total_ventas')
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // Rellenar las ventas
        foreach ($ventas_ultimos_7_dias as $venta) {
            $ventas[$venta->fecha] = $venta->total_ventas; // Asignar el total a la fecha correspondiente
        }

        return array_values($ventas); // Retornar solo los valores
    }
}
