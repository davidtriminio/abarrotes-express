<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromocionResource\Pages;
use App\Filament\Resources\PromocionResource\RelationManagers;
use App\Models\Promocion;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromocionResource extends Resource
{
    protected static ?string $model = Promocion::class;
    protected static ?string $slug = 'promociones';
    protected static ?string $modelLabel = 'promociones';

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $activeNavigationIcon = 'heroicon-s-tag';
    protected static ?string $navigationLabel = 'Promociones';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'id';
    public static function canAccess(): bool
    {
        $slug = self::getSlug();
        $usuario = auth()->user();
        if ($usuario->hasPermissionTo('ver:' . $slug)) {
            return true;
        } else {
            return false;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('producto_id')
                            ->relationship('producto', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Productos')
                            ->rules(['exists:productos,id'])
                            ->validationMessages([
                                'required' => 'Debe seleccionar un productos.',
                                'exists' => 'Los productos seleccionada no es válida.',
                            ])
                            ->options(self::getAvailableProducts()), // Llama al método para obtener productos disponibles,,

                            Forms\Components\Toggle::make('estado')
                            ->label('Estado de la promocion')
                            ->default(true)
                            ->rules(['boolean'])
                            ->validationMessages([
                                'boolean' => 'El valor debe ser verdadero o falso.',
                            ]),

                            Forms\Components\DateTimePicker::make('fecha_inicio')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y/m/d H:i:s')
                            ->label('Fecha y Hora de Inicio')
                            ->afterOrEqual(now()->startOfDay())
                            ->beforeOrEqual(now()->endOfDay())
                            ->validationMessages([
                                'required' => 'La fecha y hora de inicio son obligatorias.',
                                'after_or_equal' => 'La fecha y hora deben ser iguales o posteriores a la fecha y hora actuales.',
                                'before_or_equal' => 'La fecha y hora deben ser iguales o anteriores a la fecha y hora del final del día actual.',
                            ]),


                            Forms\Components\DateTimePicker::make('fecha_expiracion')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y/m/d H:i:s')
                            ->label('Fecha y Hora de Expiración')
                            ->after('fecha_inicio')
                            ->validationMessages([
                                'required' => 'La fecha y hora de expiración son obligatorias.',
                                'after' => 'La fecha y hora de expiración deben ser posteriores a la fecha y hora de inicio.',
                            ]),

                            Forms\Components\TextInput::make('promocion')->prefix('%')
                            ->numeric()
                            ->inputMode('decimal')
                            ->label('Promoción')
                            ->nullable()
                            ->required()
                            ->step('1')
                            ->placeholder(0)
                            ->minValue(1)
                            ->maxValue(100)
                            ->autocomplete('off')
                            ->validationMessages([
                                'required' => 'ingrese la promoción de producto.',
                                'numeric' => 'El porcentaje de oferta debe ser un número.',
                                'minValue' => 'El porcentaje de oferta debe ser al menos 1.',
                                'maxValue' => 'El porcentaje de oferta no debe ser mayor a 100.',
                            ]),
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
                Tables\Actions\EditAction::make('Editar'),
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

    public static function getAvailableProducts(): array
    {
        // Obtén los IDs de los productos que ya están relacionados con promociones
        $productosRelacionados = Promocion::pluck('producto_id')->toArray();

        // Filtra los productos que no están en la lista de productos relacionados
        return Producto::whereNotIn('id', $productosRelacionados)->pluck('nombre', 'id')->toArray();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromocions::route('/'),
            'create' => Pages\CreatePromocion::route('/create'),
            'edit' => Pages\EditPromocion::route('/{record}/edit'),
            'view' => PromocionResource\Pages\ViewPromocion::route('/{record}/view')
        ];
    }
}
