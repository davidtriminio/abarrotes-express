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
use App\Filament\Widgets\ProductosQueVanAVencer;
use App\Filament\Widgets\ProductoConCantidadBaja;
use App\Filament\Widgets\UsuarioConMasCompras;
use App\Filament\Widgets\ProductosConMasCompradas;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-list';
    protected static ?string $modelLabel = 'Inventario Avanzado';
    protected static ?string $navigationLabel = 'Inventario Avanzado';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $slug = 'inventario';

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getWidgets(): array {
        return [
            ProductosQueVanAVencer::class,
            ProductoConCantidadBaja::class,
            UsuarioConMasCompras::class,
            ProductosConMasCompradas::class,
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
