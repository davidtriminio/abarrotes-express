<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Producto;
use App\Models\User;
use App\Models\Ordenes;

class ProductosConMasCompradas extends BaseWidget
{
    protected static bool $isLazy=false;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Producto::withCount('ordenes')
                ->orderBy('ordenes_count', 'desc')
                ->limit(5)
                
            )
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre del Producto'),
            Tables\Columns\TextColumn::make('ordenes_count')
                ->label('Total de Órdenes'),
            ])
            ->paginated([5,10 ,50]);
    }

    
}
