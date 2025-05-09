<?php

namespace App\Filament\Resources\PromocionResource\Pages;

use App\Filament\Resources\PromocionResource;
use App\Traits\PermisoEditar;
use Filament\Actions;
use App\Models\Promocion;
use App\Models\Producto;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;

class EditPromocion extends EditRecord
{
    protected static string $resource = PromocionResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
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
                Select::make('producto_id')
                            ->relationship('producto', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Productos')
                            ->rules(['exists:productos,id'])
                            ->validationMessages([
                                'required' => 'Debe seleccionar un productos.',
                                'exists' => 'Los productos seleccionada no es válida.',
                            ])
                            ->options(self::getAvailableProducts()), // Llama al método estático para obtener productos disponibles

                           Toggle::make('estado')
                            ->label('Estado de la promocion')
                            ->default(true)
                            ->rules(['boolean'])
                            ->validationMessages([
                                'boolean' => 'El valor debe ser verdadero o falso.',
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
                            TextInput::make('promocion')->prefix('%')
                            ->numeric()
                            ->inputMode('decimal')
                            ->label('Promoción')
                            ->nullable()
                            ->required()
                            ->step('1')
                            ->placeholder(0)
                            ->minValue(1)
                            ->maxValue(100)
                            ->autocomplete('off')
                            ->validationMessages([
                                'required' => 'ingrese la promoción de producto.',
                                'numeric' => 'El porcentaje de oferta debe ser un número.',
                                'minValue' => 'El porcentaje de oferta debe ser al menos 1.',
                                'maxValue' => 'El porcentaje de oferta no debe ser mayor a 100.',
                            ]),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    public static function getAvailableProducts(): array
    {
        // Obtén los IDs de los productos que ya están relacionados con promociones
        $productosRelacionados = Promocion::pluck('producto_id')->toArray();
    
        // Filtra los productos que no están en oferta, que están disponibles y que no están relacionados con promociones
        return Producto::where('en_oferta', false) // Filtrar productos sin oferta
            ->where('disponible', true) // Filtrar solo productos disponibles
            ->whereNotIn('id', $productosRelacionados) // Filtrar productos que están relacionados con promociones
            ->pluck('nombre', 'id') // Obtener solo el nombre y el id
            ->toArray(); // Convertir a array
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
