<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedorResource\Pages;
use App\Filament\Resources\ProveedorResource\RelationManagers;
use App\Models\Proveedor;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;
    protected static ?string $slug = 'proveedores';
    protected static ?string $modelLabel = 'proveedores';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->label('Nombre del Proveedor')
                            ->maxLength(70)
                            ->regex('/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/')
                            ->unique(Proveedor::class, ignoreRecord: true)
                            ->autocomplete('off')
                            ->columnSpanFull()
                            ->validationMessages([
                                'max' => 'El nombre debe contener un máximo de :max caracteres.',
                                'required' => 'Debe introducir un nombre del producto.',
                                'unique' => 'Este producto ya existe.',
                                'regex' => 'El nombre del producto solo puede contener letras, números y los caracteres especiales permitidos.'
                            ]),

                        Forms\Components\Textarea::make('contracto')
                            ->required()
                            ->label('Termino de contrato')
                            ->placeholder('Debe describir los termino y las condiciones de contrato')
                            ->autosize()
                            ->minLength(15)
                            ->maxlength(300)
                            ->validationMessages([
                                'required' => 'Los termino de contrato es obligatorio.',
                                'min' => 'La descripción debe tener al menos :min caracteres.',
                                'max' => 'La descripción no puede exceder los :max caracteres.'
                            ])
                            ->columnSpan(2),

                    ])->columns(2)
                        ->columnSpan(2),


                    Section::make([

                        Forms\Components\TextInput::make('pago')->prefix('L.')
                            ->required()
                            ->inputMode('decimal')
                            ->numeric()
                            ->label('Pago')
                            ->regex('/^\d{1,10}(\.\d{1,2})?$/')
                            ->placeholder(0.00)
                            ->autocomplete('off')
                            ->step(0.01)
                            ->minValue(1)
                            ->validationMessages([
                                'required' => 'El pago es obligatorio.',
                                'numeric' => 'El pago debe ser un valor numérico.',
                                'regex' => 'El pago debe tener hasta 10 dígitos enteros y 2 decimales.',
                                'minValue' => 'El pago debe ser al menos 1.'
                            ]),

                        Forms\Components\TextInput::make('cantidad_producto')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->inputMode('numeric')
                            ->label('Cantidad de productos acordado')
                            ->placeholder(0)
                            ->step('1')
                            ->minValue(1)
                            ->autocomplete('off')
                            ->validationMessages([
                                'required' => 'La cantidad es obligatoria.',
                                'numeric' => 'La cantidad debe ser un valor numérico.',
                                'integer' => 'La cantidad debe ser un número entero.',
                                'min' => 'La cantidad disponible debe ser al menos 1.',
                            ]),

                        Forms\Components\Toggle::make('estado')
                            ->label('estado')
                            ->default(true)
                            ->rules(['boolean'])
                            ->validationMessages([
                                'boolean' => 'El valor debe ser verdadero o falso.',
                            ]),
                        Group::make([]),


                        Forms\Components\Select::make('id_producto')
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
                            ])->columnSpan(1)
                    ])->columns(3)
                ])->columns(1);

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProveedors::route('/'),
            'create' => Pages\CreateProveedor::route('/create'),
            'edit' => Pages\EditProveedor::route('/{record}/edit'),
        ];
    }
}
