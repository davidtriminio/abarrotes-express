<?php

namespace App\Filament\Resources\CuponResource\Pages;

use App\Filament\Resources\CuponResource;
use App\Models\Cupon;
use App\Traits\PermisoEditar;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;

class EditCupon extends EditRecord
{
    protected static string $resource = CuponResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    use PermisoEditar;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\DeleteAction::make('Borrar')
                ->visible(function () {
                    $slug = self::getResource()::getSlug();
                    $usuario = auth()->user();
                    if ($usuario->hasPermissionTo('borrar:' . $slug)) {
                        return true;
                    }
                    return false;
                })
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
                            ->label('Código del Cupón')
                            ->mask(99999999)
                            ->numeric()
                            ->live()
                            ->rules([
                                'required',
                                'numeric',
                                'regex:/^\d{8}$/',
                            ])
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if (empty($value)) {
                                        $fail('El código es obligatorio.');
                                        return;
                                    }

                                    if (!is_numeric($value)) {
                                        $fail('El código debe contener solo números.');
                                        return;
                                    }

                                    if (!preg_match('/^\d{8}$/', $value)) {
                                        $fail('El código debe tener exactamente 8 dígitos.');
                                        return;
                                    }

                                    $query = \App\Models\Cupon::withTrashed()
                                        ->where('codigo', $value);

                                    if ($recordId = $get('id')) {
                                        $query->where('id', '!=', $recordId);
                                    }

                                    $existingCupon = $query->first();

                                    if ($existingCupon) {
                                        if ($existingCupon->trashed()) {
                                            $fail('Este código fue eliminado, pero sigue existiendo en la base de datos. Esto debido a políticas de datos.');
                                        } else {
                                            $fail('Este código ya existe.');
                                        }
                                    }
                                };
                            })
                            ->helperText(fn ($state) => 'Quedan: ' . (8 - strlen($state)) . '/8 caracteres')
                            ->validationMessages([
                                'required' => 'El código es obligatorio.',
                                'numeric' => 'El código debe contener solo números.',
                                'regex' => 'El código debe tener exactamente 8 dígitos.',
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
                            ->afterStateUpdated(function ($state, $set) {
                                $set('descuento_porcentaje', null);
                                $set('descuento_dinero', null);

                                if ($state === 'porcentaje') {
                                    $set('compra_minima', null);
                                    $set('compra_cantidad', null);
                                }
                            })
                            ->validationMessages([
                                'required' => 'Debe seleccionar un tipo de descuento.',
                            ]),


                        TextInput::make('descuento_porcentaje')
                            ->label('Descuento en Porcentaje')
                            ->numeric()
                            ->step('0.01')
                            ->suffix('%')
                            ->required(fn($get) => $get('tipo_descuento') === 'porcentaje')
                            ->hidden(fn($get) => $get('tipo_descuento') !== 'porcentaje')
                            ->rules([
                                'numeric',
                                'min:1',
                                'max:50',
                                'regex:/^(50(\.00?)?|[1-9]?[0-9](\.\d{1,2})?)$/',
                            ])
                            ->validationMessages([
                                'numeric' => 'Debe ser un valor numérico válido.',
                                'min' => 'El porcentaje mínimo permitido es 1%.',
                                'max' => 'El porcentaje máximo permitido es 50%.',
                                'regex' => 'El descuento debe ser menor o igual al 50% y con hasta 2 decimales.',
                            ]),



                        TextInput::make('descuento_dinero')
                            ->label('Descuento en Dinero')
                            ->numeric()
                            ->step('0.01')
                            ->prefix('L.')
                            ->required(fn($get) => $get('tipo_descuento') === 'dinero')
                            ->hidden(fn($get) => $get('tipo_descuento') !== 'dinero')
                            ->rules([
                                'numeric',
                                'min:1',
                                'regex:/^\d{1,3}(\.\d{1,2})?$/',
                            ])
                            ->validationMessages([
                                'min' => 'El valor mínimo permitido es L. 1.00 ',
                                'regex' => 'El descuento debe tener hasta 3 dígitos enteros y hasta 2 decimales.',
                                'numeric' => 'Debe ser un valor numérico válido.',
                            ]),



                        DateTimePicker::make('fecha_inicio')
                            ->required()
                            ->native(false)
                            ->displayFormat('Y/m/d H:i:s')
                            ->label('Fecha y Hora de Inicio')
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $recordId = $get('id');
                                    if ($recordId) {
                                        // Estamos editando: validar contra la fecha original en BD
                                        $cupon = \App\Models\Cupon::find($recordId);
                                        if ($cupon && Carbon::parse($value)->lt(Carbon::parse($cupon->fecha_inicio))) {
                                            $fail('La nueva fecha de inicio no puede ser anterior a la fecha de inicio actual del cupón.');
                                        }
                                    }
                                };
                            })
                            ->validationMessages([
                                'required' => 'La fecha y hora de inicio son obligatorias.',
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
                            ->relationship('user', 'name')
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

                    Section::make('Restricciones (Opcional)')
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
                                ->rules([
                                    'regex:/^\d{1,3}(\.\d{1,2})?$/',
                                ])
                                ->validationMessages([
                                    'numeric' => 'Debe ser un valor numérico válido.',
                                    'regex' => 'El descuento debe tener hasta 3 dígitos enteros y hasta 2 decimales.',
                                ]),


                            TextInput::make('compra_cantidad')
                                ->label('Cantidad de Productos')
                                ->numeric()
                                ->helperText('Aplica para la cantidad de productos ingresada.')
                                ->columnSpan(1)
                                ->rules([
                                    'numeric',
                                    'min:1',
                                    'regex:/^\d{1,4}$/',
                                ])
                                ->validationMessages([
                                    'numeric' => 'Debe ser un valor numérico válido.',
                                    'min' => 'La cantidad mínima debe ser 1.',
                                    'regex' => 'La cantidad debe tener hasta 4 digitos enteros.',
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
