<?php
namespace App\Filament\Widgets;
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
        return [

        ];
    }
}
