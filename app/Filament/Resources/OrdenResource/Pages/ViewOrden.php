<?php

namespace App\Filament\Resources\OrdenResource\Pages;

use App\Filament\Resources\OrdenResource;
use App\Models\Orden;
use App\Models\Producto;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
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

class ViewOrden extends ViewRecord
{
    protected static string $resource = OrdenResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.ver-registro';
    protected static ?string $title = 'Detalles de Orden';

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
                                ->relationship('user', 'name')
                                ->label('Usuario')
                                ->searchable()
                                ->required(),

                            Select::make('metodo_pago')
                                ->required()
                                ->options([
                                    'efectivo' => 'Efectivo',
                                    'tarjeta' => 'Tarjeta de crédito o débito',
                                    'par' => 'Pago al Recibir'
                                ])
                                ->native(false),

                            Select::make('estado_pago')
                                ->options([
                                    'pagado' => 'Pagado',
                                    'procesando' => 'Procesando',
                                    'error' => 'Error'
                                ])
                                ->native(false)
                                ->required(),

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
                                    ->relationship('producto', 'nombre')
                                    ->searchable()
                                    ->required()
                                    ->distinct()
                                    ->reactive()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $producto = Producto::find($state);
                                        $set('monto_unitario', $producto ? $producto->precio : 0);
                                        $set('monto_total', ($producto ? $producto->precio : 0) * $get('cantidad'));
                                        $set('porcentaje_oferta', ($producto ? $producto->precio : 0) * $get('porcentaje_oferta'));
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
                                        $set('monto_total', $state * $get('monto_unitario'));
                                    })
                                    ->validationMessages([
                                        'required' => 'Debe introducir una cantidad',
                                    ]),

                                TextInput::make('monto_unitario')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
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
                                    ]),


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

                            Placeholder::make('total_final_placeholder')
                                ->label('Total Final: ')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('elementos')) {
                                        return $total;
                                    }

                                    foreach ($repeaters as $key => $repeater) {
                                        $total += $get("elementos.{$key}.monto_total");
                                    }

                                    return $set('total_final', $total);
                                }),

                            Placeholder::make('porcentaje_oferta_placeholder')
                                ->label('Descuentos: ')
                                ->content(function (Get $get, Set $set) {
                                    $total = 0;
                                    if (!$repeaters = $get('elementos')) {
                                        return $total;
                                    }

                                    foreach ($repeaters as $key => $repeater) {
                                        $total += $get("elementos.{$key}.porcentaje_oferta");
                                    }
                                    $set('porcentaje_oferta', $total);
                                }),


                            Placeholder::make('created_at')
                                ->label('Fecha de Creación')
                                ->content(fn(?Orden $record): string => $record?->created_at?->diffForHumans() ?? '-')
                                ->columnSpan(1),

                            Placeholder::make('updated_at')
                                ->label('Última Modificación')
                                ->content(fn(?Orden $record): string => $record?->updated_at?->diffForHumans() ?? '-')
                                ->columnSpan(1),
                        ])->columns(3),/*Fin de seccion*/
                    ])
            ]);
    }

}
