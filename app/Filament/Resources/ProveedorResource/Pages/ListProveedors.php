<?php

namespace App\Filament\Resources\ProveedorResource\Pages;

use App\Filament\Resources\ProveedorResource;
use App\Traits\PermisoVer;
use Filament\Actions;
use App\Models\Proveedor;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ListProveedors extends ListRecords
{
    protected static string $resource = ProveedorResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.lista_personalizada';
    protected static ?string $title = 'Proveedores';
    use PermisoVer;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Crear')
            ->visible(function () {
                $slug = self::getResource()::getSlug();
                $usuario = auth()->user();
                if ($usuario->hasPermissionTo('crear:' . $slug)) {
                    return true;
                }
                return false;
            })
            ->label('Crear Proveedor')
            ->icon('heroicon-o-plus-circle'),
        ];
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre'),
                TextColumn::make('pago')->label('Pago')->money('lps', true),
                TextColumn::make('cantidad_producto')->label('Cantidad Disponible'),
                TextColumn::make('producto.nombre')->label('producto'),
            ])
            ->paginated([10, 25, 50, 100,])
            ->actions([
                ViewAction::make()
                    ->hiddenLabel(),
                RestoreAction::make()
                    ->label('Restaurar')
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ])
            ]);
    }
}
