<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use Illuminate\Support\Carbon;
use App\Models\Ordenes;

class InventarioWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        $hoy = Carbon::now(); // Obtiene la fecha actual
        $quinceDiasDesdeAhora = $hoy->copy()->addDays(15); // Calcula la fecha dentro de 15 días
        return $table
            ->query(
                Producto::query()
                    ->whereBetween('fecha_expiracion', [$hoy, $quinceDiasDesdeAhora]) // Filtra productos que expiran en 15 días
            )
            ->columns([
        
                    Tables\Columns\TextColumn::make('id')->label('ID'),
                    Tables\Columns\TextColumn::make('nombre')->label('Nombre'),
                    Tables\Columns\TextColumn::make('precio')->label('Precio'),
            ]);
    }
}
