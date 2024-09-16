<?php

namespace App\Filament\Resources\OrdenResource\Widgets;

use App\Filament\Resources\OrdenResource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;

class UltimasOrdenes extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
    public function table(Table $table): Table
    {
        return $table
            ->query(OrdenResource::getEloquentQuery()->limit(5)->where('estado_entrega', 'nuevo'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Comprador'),

                TextColumn::make('total_final'),

                TextColumn::make('metodo_pago')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'par' => 'heroicon-o-banknotes',
                        'efectivo' => 'heroicon-o-currency-dollar',
                        'tarjeta' => 'heroicon-o-credit-card',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'par' => 'primary',
                        'efectivo' => 'success',
                        'tarjeta' => 'warning',
                    })
                    ->formatStateUsing((fn(string $state): string => match ($state) {
                        'par' => 'Pago al recibir',
                        'efectivo' => 'Efectivo',
                        'tarjeta' => 'Tarjeta',
                    })),

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
            ])
            ->paginated(false);
    }
}
