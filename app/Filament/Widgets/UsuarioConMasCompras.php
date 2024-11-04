<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Ordenes;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Widgets\TableWidget as BaseWidget;

class UsuarioConMasCompras extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::withCount('ordenes as total_productos') // Contar las órdenes por usuario
                ->orderBy('total_productos', 'desc') // Ordenar por total de productos
                ->take(10)
        )
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('Nombre del Usuario'),
            Tables\Columns\TextColumn::make('total_productos')->label('Cantidad'),
            ])
            ->paginated([5,10 ,50]);
    }
}
