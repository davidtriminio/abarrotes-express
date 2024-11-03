<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromocionResource\Pages;
use App\Filament\Resources\PromocionResource\RelationManagers;
use App\Models\Promocion;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ]),

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
                            ->afterOrEqual(now())
                            ->validationMessages([
                                'required' => 'La fecha y hora de inicio son obligatorias.',
                                'after_or_equal' => 'La fecha y hora deben ser iguales o posteriores a la fecha y hora actuales.',
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
                            ->label('Promocion')
                            ->nullable()
                            ->step('1')
                            ->placeholder(0)
                            ->minValue(1)
                            ->maxValue(100)
                            ->autocomplete('off')
                            ->validationMessages([
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
