<?php

namespace App\Filament\Resources\MarcaResource\Pages;

use App\Filament\Resources\MarcaResource;
use App\Models\Marca;
use App\Traits\PermisoEditar;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EditMarca extends EditRecord
{
    protected static string $resource = MarcaResource::class;
    protected ?string $heading = '';
    protected static string $view = 'filament.resources.custom.editar-registro';
    protected static ?string $title = 'Editar Marca';
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
                TextInput::make('nombre')
                    ->label('Nombre De la Marca')
                    ->maxLength(70) // Esto se mantiene para el contador
                    ->hint(fn ($state, $component) => ($component->getMaxLength() - strlen($state) . '/' . 70 . ' caracteres restantes.'))
                    ->live()
                    ->regex('/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/')
                    ->autocomplete('off')
                    ->rules([
                        'required',
                        'regex:/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/',
                    ])
                    ->rule(function (Forms\Get $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if (empty($value)) {
                                $fail('El nombre es obligatorio.');
                                return;
                            }

                            if (strlen($value) < 5) {
                                $fail('El nombre debe tener al menos 5 caracteres.');
                                return;
                            }

                            if (strlen($value) > 70) {
                                $fail('El nombre debe contener un máximo de 70 caracteres.');
                                return;
                            }

                            if (!preg_match('/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/', $value)) {
                                $fail('El nombre solo debe contener letras y números.');
                                return;
                            }

                            $query = \App\Models\Marca::withTrashed()
                                ->where('nombre', $value);

                            if ($recordId = $get('id')) {
                                $query->where('id', '!=', $recordId);
                            }

                            $existingCategoria = $query->first();

                            if ($existingCategoria) {
                                if ($existingCategoria->trashed()) {
                                    $fail('Esta categoría fue eliminada, pero sigue existiendo en la base de datos. Esto debido a políticas de datos.');
                                } else {
                                    $fail('Esta categoría ya existe.');
                                }
                            }
                        };
                    })
                    ->validationMessages([
                        'required' => 'El nombre es obligatorio.',
                        'regex' => 'El nombre solo debe contener letras y números.',
                    ])
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('imagen')
                    ->required()
                    ->label('Imagen')
                    ->image()
                    ->directory('marcas')
                    ->maxFiles(1)
                    ->maxSize(2048)
                    ->preserveFilenames()
                    ->validationMessages([
                        'maxFiles' => 'Se permite un máximo de 1 imagen.',
                        'required' => 'Debe seleccionar al menos una imagen.',
                        'image' => 'El archivo debe ser una imagen válida.',
                        'max' => 'El tamaño de la imagen no debe exceder los 2MB.',
                    ])
                    ->columnSpanFull(),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->placeholder('Escribe una breve descripción...')
                    ->maxLength(300)
                    ->hint(fn ($state, $component) => ($component->getMaxLength() - strlen($state) . '/' . $component->getMaxLength() . ' caracteres restantes.'))
                    ->live()
                    ->autosize()
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            if (empty($value)) {
                                $fail('La descripción es obligatoria.');
                                return;
                            }

                            if (strlen($value) < 5) {
                                $fail('La descripción debe tener al menos 5 caracteres.');
                                return;
                            }

                            if (strlen($value) > 300) {
                                $fail('La descripción no puede exceder los 300 caracteres.');
                                return;
                            }
                        };
                    })
                    ->validationMessages([
                        'required' => 'La descripción es obligatoria.',
                        'min' => 'La descripción debe tener al menos :min caracteres.',
                        'max' => 'La descripción no puede exceder los :max caracteres.'
                    ])
                    ->columnSpan(2),
            ]);
    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }


}
