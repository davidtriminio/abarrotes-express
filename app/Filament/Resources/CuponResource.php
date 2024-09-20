<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CuponResource\Pages;
use App\Models\Cupon;
use App\Filament\Resources\ProductoResource\RelationManagers;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CuponResource extends Resource
{
    protected static ?string $model = Cupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $pluralLabel = "Cupones";

    protected static ?string $navigationGroup = 'Productos';

    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'codigo';
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([
                        TextInput::make('codigo')
                            ->mask(99999999)
                            ->required()
                            ->numeric()
                            ->label('Código del Cupón')
                            ->helperText(fn ($state, $component) => 'Quedan: ' . (8 - strlen($state)) . '/8 caracteres')
                            ->live()
                            ->unique(Cupon::class, ignoreRecord: true)                            ->validationMessages([
                                'required' => 'El código es obligatorio.',
                                'max_digits' => 'El código debe tener solamente 8 dígitos.',
                                'mask' => 'El código debe tener solamente 8 dígitos.',
                                'unique' => 'Este código ya existe.',
                            ]),

                        Forms\Components\Select::make('tipo_descuento')
                            ->required()
                            ->label('Tipo de Descuento')
                            ->options([
                                'porcentaje' => 'Porcentaje',
                                'dinero' => 'Dinero',
                            ])
                            ->reactive()
                            ->afterStateUpdated(fn($state, $set) => $set('descuento_porcentaje', null) && $set('descuento_dinero', null)),

                        Forms\Components\TextInput::make('descuento_porcentaje')
                            ->label('Descuento en Porcentaje')
                            ->numeric()
                            ->step('0.01')
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(fn($get) => $get('tipo_descuento') === 'porcentaje')
                            ->hidden(fn($get) => $get('tipo_descuento') !== 'porcentaje')
                            ->validationMessages([
                                'regex' => 'El descuento debe tener como máximo dos dígitos enteros y dos decimales.',
                            ]),

                        Forms\Components\TextInput::make('descuento_dinero')
                            ->label('Descuento en Dinero')
                            ->numeric()
                            ->step('0.01')
                            ->minValue(1)
                            ->maxValue(2000.00)
                            ->prefix('L.')
                            ->required(fn($get) => $get('tipo_descuento') === 'dinero')
                            ->hidden(fn($get) => $get('tipo_descuento') !== 'dinero')
                            ->regex('/^\d{1,5}(\.\d{1,2})?$/')
                            ->validationMessages([
                                'max' => 'El valor máximo permitido es 2000.00 L.',
                                'regex' => 'El descuento debe tener hasta 5 dígitos enteros y hasta 2 decimales.',
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

                        Forms\Components\Select::make('usuario_id')
                            ->relationship('usuario', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->label('Usuario')
                            ->exists('users', 'id')
                            ->validationMessages([
                                'required' => 'Debe seleccionar un usuario.',
                                'exists' => 'El usuario seleccionado no es válido.'
                            ]),



                        Forms\Components\Select::make('producto_id')
                            ->relationship('producto', 'nombre')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->label('Producto')
                            ->exists('productos', 'id'),

                        Forms\Components\Select::make('categoria_id')
                            ->relationship('categoria', 'nombre')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->label('Categoría')
                            ->exists('categorias', 'id'),

                        Forms\Components\Select::make('marca_id')
                            ->relationship('marca', 'nombre')
                            ->nullable()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->label('Marca')
                            ->exists('marcas', 'id'),


                        Forms\Components\Toggle::make('estado')
                            ->label('Estado')
                            ->default(true),
                    ])->columns(3)
                ])->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código del Cupón'),
                Tables\Columns\TextColumn::make('descuento')
                    ->label('Descuento'),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha de Inicio'),

                Tables\Columns\TextColumn::make('fecha_expiracion')
                    ->label('Fecha de Expiración'),

                Tables\Columns\IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Usuario'),
                Tables\Columns\TextColumn::make('producto.nombre')
                    ->label('Producto'),
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría'),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCupons::route('/'),
            'create' => Pages\CreateCupon::route('/create'),
            'edit' => Pages\EditCupon::route('/{record}/edit'),
            'view' => Pages\ViewCupon::route('/{record}/view')
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CuponResource::getUrl('view', ['record' => $record]);
    }

    public static function canAccess(): bool
    {
        $usuario = auth()->user();
        return $usuario->hasPermissionTo('ver:cupon');
    }
}