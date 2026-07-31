<?php

namespace App\Filament\Resources\ProveedorResource\Pages;

use App\Filament\Resources\ProveedorResource;
use App\Traits\PermisoEditar;
use Filament\Actions;
use App\Models\Proveedor;
use App\Models\Producto;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;


class EditProveedor extends EditRecord
{
    protected static string $resource = ProveedorResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    protected static ?string $title = 'Detalles del Proveedor';
    use PermisoEditar;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
            ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
            ->button()
            ->icon('heroicon-o-chevron-left')
            ->color('gray'),
        DeleteAction::make('Borrar')
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
                            ->maxValue(100000)
                            ->validationMessages([
                                'required' => 'El pago es obligatorio.',
                                'numeric' => 'El pago debe ser un valor numérico.',
                                'regex' => 'El pago debe tener hasta 10 dígitos enteros y 2 decimales.',
                                'minValue' => 'El pago debe ser al menos 1.'
                            ]),

                        TextInput::make('cantidad_producto')
                        ->required()
                            ->inputMode('decimal')
                            ->numeric()
                            ->label('Cantidad de Producto')
                            ->regex('/^\d{1,10}(\.\d{1,2})?$/')
                            ->placeholder(0.00)
                            ->autocomplete('off')
                            ->step(0.01)
                            ->minValue(1)
                            ->maxValue(10000)
                            ->validationMessages([
                                'required' => 'La cantidad de producto es obligatorio.',
                                'numeric' => 'La cantidad debe ser un valor numérico.',
                                'regex' => 'El pago debe tener hasta 10 dígitos enteros y 2 decimales.',
                                'minValue' => 'La cantidad debe ser al menos 1.'
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
