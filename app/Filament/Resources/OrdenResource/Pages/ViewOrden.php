<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\User;
use App\Traits\PermisoVer;
use Filament\Actions;
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
use Filament\Resources\Pages\ViewRecord;
use Nette\Utils\Html;

class ViewOrden extends ViewRecord
{
    protected static string $resource = OrdenResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.ver-registro';
    protected static ?string $title = 'Detalles de Orden';
    use PermisoVer;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\EditAction::make('Editar')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if (auth()->user()->hasPermissionTo('editar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->icon('heroicon-o-pencil-square'),
            Actions\DeleteAction::make('Borrar')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if (auth()->user()->hasPermissionTo('borrar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
                ->icon('heroicon-o-trash'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([
                        Section::make([
                            Select::make('user_id')
                                ->relationship('user', 'email')
                                ->getSearchResultsUsing(function (string $search){
                                    return User::query()
                                        ->where('email', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                                        ->get(['id', 'name', 'email'])
                                        ->mapWithKeys(function ($usuario){
                                            return [$usuario->id => "{$usuario->name} ({$usuario->email})"];
                                        });
                                })
                                ->getOptionLabelsUsing(function ($valor){
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
                                            $precio = $producto->precio;
                                            if ($producto->en_oferta) {
                                                $porcentajeDescuento = $producto->porcentaje_oferta / 100;
                                                $precioConDescuento = $precio - ($precio * $porcentajeDescuento);
                                            } else {
                                                $precioConDescuento = $precio;
                                            }
                                            $set('monto_unitario', number_format((float)$precioConDescuento, 2, '.', ''));
                                            $set('monto_total', number_format((float)($precioConDescuento * $get('cantidad')), 2, '.', ''));

                                            if ($producto->en_oferta) {
                                                $set('hint_monto_unitario', Html::htmlToText("<s>L. " . number_format($precio, 2) . "</s>"));
                                            } else {
                                                $set('hint_monto_unitario', null);
                                            }

                                            // Validación de cantidad
                                            $cantidadDisponible = $producto->cantidad_disponible;
                                            $cantidadSolicitada = $get('cantidad');

                                            if ($cantidadSolicitada > $cantidadDisponible) {
                                                $set('cantidad', $cantidadDisponible);
                                                $set('error_cantidad', "La cantidad disponible es solo $cantidadDisponible.");
                                            } else {
                                                $set('error_cantidad', null);
                                            }
                                        }
                                    })
                                    ->validationMessages([
                                        'required' => 'Debe seleccionar un producto.',
                                    ])
                                    ->hint(function ($state, $component) {
                                        $producto = Producto::find($state);
                                        return $producto ? "Cantidad disponible: {$producto->cantidad_disponible}" : 'Seleccione un producto.';
                                    })
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
                                        }
                                    })
                                    ->columns(3)
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
                                ->content(fn(?Orden $record): string => $record?->descuento_total ? 'L. ' . number_format($record->descuento_total, 2) : '-')
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

}
