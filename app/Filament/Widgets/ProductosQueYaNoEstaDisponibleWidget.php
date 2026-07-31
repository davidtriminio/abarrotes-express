<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Producto;
use App\Models\Marca;

class ProductosQueYaNoEstaDisponibleWidget extends BaseWidget
{
    protected static bool $isLazy=false;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Producto::query()
                    ->join('marcas', 'marcas.id', '=', 'productos.marca_id')
                    ->where('productos.disponible', '=', false)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->label('Nombre'),
                Tables\Columns\TextColumn::make('marca.nombre')->label('Marca'),
            ]); // Define la clave única
            
    }
}
