<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Producto;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductoConCantidadBaja extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Producto::query()
                    ->where('cantidad_disponible','<=',20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('nombre')->label('Nombre'),
                Tables\Columns\TextColumn::make('cantidad_disponible')->label('Precio'),
            ]);
    }
}
