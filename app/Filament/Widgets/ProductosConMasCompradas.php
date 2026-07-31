<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Producto;
use App\Models\User;
use App\Models\ElementoOrden;
use App\Models\Orden;

class ProductosConMasCompradas extends BaseWidget
{
    protected static bool $isLazy=false;
    public function table(Table $table): Table
    {
        return $table
        ->query(
            Producto::withCount('elementosOrden') // Nota: cambiado a plural
                ->orderBy('elementos_orden_count', 'desc')
                ->take(5)
        )
        ->columns([
            Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre del Producto'),
            Tables\Columns\TextColumn::make('elementos_orden_count')
                ->label('Productos mas venido'),
        ]);
    }

    
}
