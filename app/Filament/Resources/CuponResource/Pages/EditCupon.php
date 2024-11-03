<?php

namespace App\Filament\Resources\CuponResource\Pages;

use App\Filament\Resources\CuponResource;
use App\Models\Cupon;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditCupon extends EditRecord
{
    protected static string $resource = CuponResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\DeleteAction::make('Borrar')
                ->icon('heroicon-o-trash'),
        ];
    }

    public  function form(Form $form): Form
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
                            ->unique(Cupon::class, ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'El código es obligatorio.',
                                'max_digits' => 'El código debe tener solamente 8 dígitos.',
                                'mask' => 'El código debe tener solamente 8 dígitos.',
                                'unique' => 'Este código ya existe.',
                            ]),

                        Select::make('tipo_descuento')
                            ->required()
                            ->native(false)
                            ->label('Tipo de Descuento')
                            ->options([
                                'porcentaje' => 'Porcentaje',
                                'dinero' => 'Dinero',
                            ])
                            ->reactive()
                            ->afterStateUpdated(fn($state, $set) => $set('descuento_porcentaje', null) && $set('descuento_dinero', null)),

                        TextInput::make('descuento_porcentaje')
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

                        TextInput::make('descuento_dinero')
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

                        DateTimePicker::make('fecha_inicio')
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


                        DateTimePicker::make('fecha_expiracion')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y/m/d H:i:s')
                            ->label('Fecha y Hora de Expiración')
                            ->after('fecha_inicio')
                            ->validationMessages([
                                'required' => 'La fecha y hora de expiración son obligatorias.',
                                'after' => 'La fecha y hora de expiración deben ser posteriores a la fecha y hora de inicio.',
                            ]),

                        Select::make('user_id')
                            ->relationship('users', 'name')
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


                        Toggle::make('estado')
                            ->label('Estado')
                            ->default(true),
                    ])->columns(3),

                    Section::make('Restricciones')
                        ->schema([


                            TextInput::make('compra_minima')
                                ->label('Compra mínima a:')
                                ->numeric()
                                ->step('0.01')
                                ->helperText('Aplica para compras mínima a la cantidad de dinero ingresada.')
                                ->prefix('L.')
                                ->minValue(0)
                                ->columnSpan(1)
                                ->disabled(fn ($get) => $get('tipo_descuento') === 'porcentaje')
                                ->validationMessages([
                                    'numeric' => 'Debe ser un valor numérico válido.',
                                ]),

                            TextInput::make('compra_cantidad')
                                ->label('Cantidad de Productos')
                                ->numeric()
                                ->helperText('Aplica para la cantidad de productos ingresada.')
                                ->minValue(1)
                                ->maxValue(100)
                                ->columnSpan(1)
                                ->validationMessages([
                                    'numeric' => 'Debe ser un valor numérico válido.',
                                    'min' => 'La cantidad mínima debe ser 1.',
                                ]),

                        ])->columns(2),
                ])->columnSpan(2),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
