<?php

namespace App\Filament\Resources\ProveedorResource\Pages;

use App\Filament\Resources\ProveedorResource;
use Filament\Actions;
use App\Models\Proveedor;
use App\Models\Producto;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;

class EditProveedor extends EditRecord
{
    protected static string $resource = ProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
            ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
            ->button()
            ->icon('heroicon-o-chevron-left')
            ->color('gray'),
        DeleteAction::make()
            ->icon('heroicon-o-trash'),
        ];
    }

    public function form(Form $form): Form
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
                                'required' => 'Debe introducir un nombre del proveedor.',
                                'unique' => 'Este proveedor ya existe.',
                                'regex' => 'El nombre del proveedor solo puede contener letras, números y los caracteres especiales permitidos.'
                            ]),


                        Forms\Components\Textarea::make('contracto')
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

                        Forms\Components\TextInput::make('pago')->prefix('L.')
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

                        Forms\Components\TextInput::make('cantidad_producto')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->inputMode('numeric')
                            ->label('Cantidad Producto acordado')
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
                            ->default(false)
                            ->rules(['boolean'])
                            ->validationMessages([
                                'boolean' => 'El valor debe ser verdadero o falso.',
                            ]),

                    ])->columnSpan(1)
                ])->columns(3)
            ])->columns(1);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
