<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ListOrdenes extends ListRecords
{
    protected static string $resource = OrdenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Orden')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todo'),
            'nuevo' => Tab::make('Nuevo')->query(fn($query) => $query->where('estado_entrega', 'nuevo'))
                ->icon('heroicon-o-sparkles'),
            'procesado' => Tab::make('En Proceso')->query(fn($query) => $query->where('estado_entrega', 'procesado'))
                ->icon('heroicon-o-arrow-path'),
            'enviado' => Tab::make('Enviado')->query(fn($query) => $query->where('estado_entrega', 'enviado'))
                ->icon('heroicon-o-truck'),
            'entregado' => Tab::make('Entregado')->query(fn($query) => $query->where('estado_entrega', 'entregado'))
                ->icon('heroicon-o-archive-box'),
            'cancelado' => Tab::make('Cancelado')->query(fn($query) => $query->where('estado_entrega', 'cancelado'))
                ->icon('heroicon-o-x-circle'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Comprador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_final')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('metodo_pago')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('estado_pago')
                    ->options([
                        'pagado' => 'Pagado',
                        'procesando' => 'Procesando',
                        'error' => 'Error'
                    ])
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('estado_entrega')
                    ->options([
                        'nuevo' => 'Nuevo',
                        'procesado' => 'En Proceso',
                        'enviado' => 'Enviado',
                        'entregado' => 'Entregado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->searchable()
                    ->sortable(),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                    ->label('Restaurar')
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ])
            ]);
    }
}
