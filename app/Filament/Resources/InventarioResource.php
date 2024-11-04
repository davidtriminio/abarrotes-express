<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrdenResource\Widgets\EstadisticasOrdenes;
use App\Filament\Resources\OrdenResource\Widgets\OrdenesTotales;
use App\Filament\Resources\OrdenResource\Widgets\ProductosMasVendidos;
use App\Filament\Resources\OrdenResource\Widgets\UltimasOrdenes;
use App\Filament\Widgets\EstadisticasVentas;
use App\Filament\Widgets\InventarioWidget;
use App\Filament\Widgets\ProductoConCantidadBaja;
use App\Filament\Widgets\UsuarioConMasCompras;
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


class InventarioResource extends Resource
{
    
    protected static ?string $model = null;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate() : bool{
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getWidgets(): array {
        return [
            InventarioWidget::class,
            ProductoConCantidadBaja::class,
            UsuarioConMasCompras::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventarios::route('/'),
           // 'create' => Pages\CreateInventario::route('/create'),
           // 'edit' => Pages\EditInventario::route('/{record}/edit'),
        ];
    }
}
