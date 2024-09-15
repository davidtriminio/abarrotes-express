<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Filament\Resources\OrdenResource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimasOrdenes extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(OrdenResource::getEloquentQuery())
            ->paginated(5)
            ->paginationPageOptions([1,5,10])
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Comprador'),

                TextColumn::make('total_final'),

                TextColumn::make('metodo_pago'),

                SelectColumn::make('estado_pago')
                    ->options([
                        'pagado' => 'Pagado',
                        'procesando' => 'Procesando',
                        'error' => 'Error'
                    ]),
                SelectColumn::make('estado_entrega')
                    ->options([
                        'nuevo' => 'Nuevo',
                        'procesado' => 'En Proceso',
                        'enviado' => 'Enviado',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ])
            ]);
    }
}
