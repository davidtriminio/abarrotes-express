<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenResource\Pages;
use App\Filament\Resources\OrdenResource\RelationManagers\DireccionRelationManager;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nette\Utils\Html;

class OrdenResource extends Resource
{
    protected static ?string $model = Orden::class;

    protected static ?string $slug = 'ordenes';
    protected static ?string $modelLabel = 'Ordenes';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $activeNavigationIcon =
        'heroicon-s-truck';

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

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return OrdenResource::getUrl('view', ['record' => $record]);
    }

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
                        Section::make([
                            Select::make('user_id')
                                ->relationship('user', 'email')
                                ->getSearchResultsUsing(function (string $search) {
                                    return User::query()
                                        ->where('email', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                                        ->get(['id', 'name', 'email'])
                                        ->mapWithKeys(function ($usuario) {
                                            return [$usuario->id => "{$usuario->name} ({$usuario->email})"];
                                        });
                                })
                                ->getOptionLabelsUsing(function ($valor) {
                                    $usuario = User::find($valor);
                                    return $usuario ? "{$usuario->name} ({$usuario->email})" : null;
                                })
                                ->exists('users', 'id')
                                ->label('Usuario')
                                ->searchable()
                                ->required()
                                ->validationMessages([
                                    'relationship' => 'Se debe seleccionar un usuario existente.',
                                    'exists' => 'Debe seleccionar un usuario existente.',
                                    'required' => 'Se debe seleccionar un comprador.'
                                ]),

                            Select::make('metodo_pago')
                                ->required()
                                ->options([
                                    'efectivo' => 'Efectivo',
                                    'tarjeta' => 'Tarjeta de crédito o débito',
                                    'par' => 'Pago al Recibir'
                                ])
                                ->native(false)
                                ->default('par')
                                ->validationMessages([
                                    'required' => 'Debe seleccionar un metodo de pago.',
                                    'options' => 'Debe seleccionar un metodo de pago valido.'
                                ]),

                            Select::make('estado_pago')
                                ->options([
                                    'pagado' => 'Pagado',
                                    'procesando' => 'Procesando',
                                    'error' => 'Error'
                                ])
                                ->default('procesando')
                                ->native(false)
                                ->required()
                                ->validationMessages([
                                    'options' => 'Debe seleccionar un metodo de pago valido.',
                                    'required' => 'Debe seleccionar un metodo de pago.',
                                ]),

                            ToggleButtons::make('estado_entrega')
                                ->options([
                                    'nuevo' => 'Nuevo',
                                    'procesado' => 'Procesando',
                                    'enviado' => 'Enviado',
                                    'entregado' => 'Entregado',
                                    'cancelado' => 'Cancelado'
                                ])
                                ->colors([
                                    'nuevo' => 'primary',
                                    'procesado' => 'warning',
                                    'enviado' => 'success',
                                    'entregado' => 'success',
                                    'cancelado' => 'danger'
                                ])
                                ->icons([
                                    'nuevo' => 'heroicon-m-sparkles',
                                    'procesado' => 'heroicon-m-arrow-path',
                                    'enviado' => 'heroicon-m-truck',
                                    'entregado' => 'heroicon-m-archive-box',
                                    'cancelado' => 'heroicon-m-x-circle'
                                ])
                                ->default('nuevo')
                                ->inline()
                                ->required()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    if ($state === 'entregado' && !$get('fecha_entrega')) {
                                        $set('fecha_entrega', \Carbon\Carbon::now());
                                    }
                                })
                                ->validationMessages([
                                    'options' => 'Debe seleccionar un estado de entrega válido.',
                                    'required' => 'Debe seleccionar un estado de entrega.',
                                ])
                                ->live(),
                            DateTimePicker::make('fecha_entrega')
                                ->visible(function (Get $get, Set $set) {
                                    if ($get('estado_entrega') === 'entregado' && !$get('fecha_entrega')) {
                                        $set('fecha_entrega', \Carbon\Carbon::now());
                                    }
                                    return $get('estado_entrega') === 'entregado';
                                })
                                ->live()
                                ->native(false)
                                ->minDate(fn(Get $get) => $get('created_at'))
                        ])->columns(2),
                    ])->columns(2)->columnSpanFull(),
                ])->columnSpanFull(),

                Section::make('Detalles de Orden')
                    ->schema([
                        Repeater::make('elementos')
                            ->relationship()
                            ->schema([
                                Select::make('producto_id')
                                    ->preload()
                                    ->relationship('producto', 'nombre', function ($query) {
                                        $query->where('disponible', true)
                                            ->where('cantidad_disponible', '>', 0);
                                    })
                                    ->searchable()
                                    ->required()
                                    ->distinct()
                                    ->reactive()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $producto = Producto::find($state);
                                        if ($producto) {
                                            $precioOriginal = $producto->precio;
                                            $precioConDescuento = $producto->en_oferta
                                                ? $precioOriginal - ($precioOriginal * ($producto->porcentaje_oferta / 100))
                                                : $precioOriginal;

                                            $descuentoPorProducto = $producto->en_oferta
                                                ? $precioOriginal - $precioConDescuento
                                                : 0;

                                            $cantidad = $get('cantidad') ?? 1;
                                            $descuentoTotalProducto = $descuentoPorProducto * $cantidad;

                                            $set('monto_unitario', number_format((float)$precioConDescuento, 2, '.', ''));
                                            $set('monto_total', number_format((float)($precioConDescuento * $cantidad), 2, '.', ''));
                                            $set('descuento_total_producto', number_format((float)$descuentoTotalProducto, 2, '.', ''));

                                            // Mostrar el precio original en rojo si está en oferta
                                            if ($producto->en_oferta) {
                                                $set('hint_monto_unitario', Html::htmlToText("<s style='color: red;'>L. " . number_format($precioOriginal, 2) . "</s>"));
                                            } else {
                                                $set('hint_monto_unitario', null);
                                            }
                                        }
                                    })
                                    ->validationMessages([
                                        'required' => 'Debe seleccionar un producto.',
                                    ])
                                    ->hint(fn(Get $get) => $get('hint_monto_unitario'))
                                    ->columnSpan(4),
                                TextInput::make('cantidad')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $producto = Producto::find($get('producto_id'));

                                        if ($producto) {
                                            $cantidadDisponible = $producto->cantidad_disponible;
                                            if ($state > $cantidadDisponible) {
                                                $set('cantidad', $cantidadDisponible);
                                            }
                                            $monto_unitario = $get('monto_unitario');
                                            $set('monto_total', number_format((float)($get('cantidad') * $monto_unitario), 2, '.', ''));
                                            $set('monto_unitario', number_format((float)$monto_unitario, 2, '.', ''));

                                            // Recalcular el descuento total por producto
                                            $precioOriginal = $producto->precio;
                                            $precioConDescuento = $producto->en_oferta
                                                ? $precioOriginal - ($precioOriginal * ($producto->porcentaje_oferta / 100))
                                                : $precioOriginal;

                                            $descuentoPorProducto = $producto->en_oferta
                                                ? $precioOriginal - $precioConDescuento
                                                : 0;

                                            $descuentoTotalProducto = $descuentoPorProducto * $state;
                                            $set('descuento_total_producto', number_format((float)$descuentoTotalProducto, 2, '.', ''));
                                        }
                                    })
                                    ->validationMessages([
                                        'required' => 'Debe introducir una cantidad',
                                        'min_value' => 'La cantidad mínima permitida es 1',
                                        'max_value' => 'La cantidad no puede ser mayor que la cantidad disponible'
                                    ])
                                    ->columnSpan(2),
                                TextInput::make('monto_unitario')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->label('Monto Unitario')
                                    ->hint(fn(Get $get) => $get('hint_monto_unitario'))
                                    ->hintColor('danger')
                                    ->reactive()
                                    ->formatStateUsing(fn($state) => is_numeric($state) ? number_format((float)$state, 2) : $state)
                                    ->validationMessages([
                                        'disabled' => 'El campo no puede ser deshabilitado',
                                        'numeric' => 'El valor ingresado debe ser un número',
                                        'required' => 'Debe introducir una cantidad',
                                        'min_value' => 'La cantidad mínima permitida es 1'
                                    ])
                                    ->columnSpan(3),

                                TextInput::make('monto_total')
                                    ->numeric()
                                    ->disabled()
                                    ->required()
                                    ->dehydrated()
                                    ->reactive()
                                    ->step(0.01)
                                    ->columnSpan(3)
                                    ->extraAttributes([
                                        'step' => '0.01'
                                    ])
                                    ->formatStateUsing(fn($state) => is_numeric($state) ? number_format((float)$state, 2) : $state)
                                    ->validationMessages([
                                        'disabled' => 'El campo no puede ser deshabilitado',
                                        'numeric' => 'El valor ingresado debe ser un número',
                                        'required' => 'Debe introducir una cantidad',
                                        'min_value' => 'La cantidad mínima permitida es 1'
                                    ])->columns(2),
                            ])->columns(12),

                        Section::make([
                            Textarea::make('notas')
                                ->required()
                                ->label('Descripción')
                                ->placeholder('Escribe una breve descripción...')
                                ->autosize()
                                ->minLength(5)
                                ->maxlength(300)
                                ->validationMessages([
                                    'required' => 'La descripción es obligatoria.',
                                    'min' => 'La descripción debe tener al menos :min caracteres.',
                                    'max' => 'La descripción no puede exceder los :max caracteres.'
                                ])
                                ->columnSpanFull(),
                        ]),

                        Section::make([
                            Placeholder::make('sub_total_placeholder')
                                ->label('Subtotal:')
                                ->content(fn(?Orden $record): string => $record?->sub_total ? 'L. ' . number_format($record->sub_total, 2) : '-')
                                ->columnSpan(1),

                            Hidden::make('total_final')
                                ->default(0),

                            Hidden::make('costos_envio')
                                ->default(0),

                            Placeholder::make('descuento_total_placeholder')
                                ->label('Descuento Total:')
                                ->content(fn(Get $get) => 'L. ' . number_format($get('descuento_total') ?? 0, 2))
                                ->columnSpan(1),

                            Placeholder::make('total_final_placeholder')
                                ->label('Total Final:')
                                ->content(fn(?Orden $record): string => $record?->total_final ? 'L. ' . number_format($record->total_final, 2) : '-')
                                ->columnSpan(1),
                            Section::make([
                                Placeholder::make('created_at')
                                    ->label('Fecha de Creación')
                                    ->content(fn(?Orden $record): string => $record?->created_at?->diffForHumans() ?? '-')
                                    ->columnSpan(1),
                                Placeholder::make('updated_at')
                                    ->label('Última Modificación')
                                    ->content(fn(?Orden $record): string => $record?->updated_at?->diffForHumans() ?? '-')
                                    ->columnSpan(1),
                            ])->columns(2),
                        ])->columns(3),/*Fin de seccion*/
                    ])
            ]);
    }

    public static function table(Table $table): Table
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
            ->filters([
                //
            ])
            ->actions([
                EditAction::make('Editar'),
                DeleteAction::make('Borrar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdenes::route('/'),
            'create' => Pages\CreateOrden::route('/create'),
            'edit' => Pages\EditOrden::route('/{record}/edit'),
            'view' => Pages\ViewOrden::route('/{record}/view')
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'id'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['Usuario'] = $record->user->name;
        }

        return $details;
    }

    public static function getRelations(): array
    {
        return [
            DireccionRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return self::getModel()::count() > 5 ? 'success' : 'danger';
    }

    protected function beforeSave(): void
    {
        $descuentoTotal = 0;

        foreach ($this->data['elementos'] as $elemento) {
            $descuentoTotal += $elemento['descuento_total_producto'] ?? 0;
        }

        $this->record->descuento_total = $descuentoTotal;
    }
}
