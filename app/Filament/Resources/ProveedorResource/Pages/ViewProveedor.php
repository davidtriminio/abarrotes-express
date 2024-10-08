<?php

namespace App\Filament\Resources\ProveedorResource\Pages;

use App\Filament\Resources\ProveedorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Proveedor;
use App\Models\Producto;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class ViewProveedor extends ViewRecord
{
    protected static string $resource = ProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make([
                        TextInput::make('nombre')
                            ->required()
                            ->label('Nombre del Proveedor')
                            ->maxLength(70)
                            ->regex('/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/')
                            ->unique(Proveedor::class, ignoreRecord: true)
                            ->autocomplete('off')
                            ->columnSpanFull()
                            ->validationMessages([
                                'max' => 'El nombre debe contener un máximo de :max caracteres.',
                                'required' => 'Debe introducir un nombre del proveedor.',
                                'unique' => 'Este proveedor ya existe.',
                                'regex' => 'El nombre del proveedor solo puede contener letras, números y los caracteres especiales permitidos.'
                            ]),


                        Textarea::make('contracto')
                            ->required()
                            ->label('Descripción de contrato')
                            ->placeholder('Escribe una breve descripción de contrato...')
                            ->autosize()
                            ->minLength(5)
                            ->maxlength(300)
                            ->validationMessages([
                                'required' => 'La descripción es obligatoria.',
                                'min' => 'La descripción debe tener al menos :min caracteres.',
                                'max' => 'La descripción no puede exceder los :max caracteres.'
                            ])
                            ->columnSpan(2),

                    ])->columns(2)
                        ->columnSpan(2),


                    Section::make([

                        TextInput::make('pago')->prefix('L.')
                            ->required()
                            ->inputMode('decimal')
                            ->numeric()
                            ->label('Pago a proveedor')
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

                        TextInput::make('cantidad_producto')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->inputMode('numeric')
                            ->label('Cantidad Producto')
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

                            Select::make('id_producto')
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

                        Toggle::make('estado')
                            ->label('Estado de contrato')
                            ->default(false)
                            ->rules(['boolean'])
                            ->validationMessages([
                                'boolean' => 'El valor debe ser verdadero o falso.',
                            ]),

                    ])->columnSpan(1)
                ])->columns(3)
            ])->columns(1);
    }
}
