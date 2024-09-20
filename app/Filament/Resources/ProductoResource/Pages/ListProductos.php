<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use App\Filament\Resources\ProductoResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ListProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Producto')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->label('Nombre'),
                Tables\Columns\TextColumn::make('precio')->label('Precio')->money('lps', true),
                Tables\Columns\TextColumn::make('cantidad_disponible')->label('Cantidad Disponible')
                ->alignCenter(),
                Tables\Columns\TextColumn::make('marca.nombre')->label('Marca'),
                Tables\Columns\TextColumn::make('categoria.nombre')->label('Categoría'),

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