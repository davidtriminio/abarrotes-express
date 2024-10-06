<?php

namespace App\Filament\Resources\CategoriaResource\Pages;

use App\Filament\Resources\CategoriaResource;
use App\Models\Categoria;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required()
                    ->label('Nombre De la Categoria')
                    ->maxLength(70)
                    ->hint(fn ($state, $component) => ($component->getMaxLength() - strlen($state) . '/' . $component->getMaxLength() . ' caracteres restantes.'))
                    ->live()
                    ->regex('/^[A-Za-zÀ-ÿ0-9\s\-\'\.]+$/')
                    ->unique(Categoria::class, ignoreRecord: true)
                    ->autocomplete('off')
                    ->validationMessages([
                        'maxLength' => 'El nombre debe contener un máximo de :max caracteres.',
                        'required' => 'Debe introducir un nombre para la categoría.',
                        'regex' => 'El nombre solo debe contener letras y espacios.',
                        'unique' => 'Esta categoría ya existe.',
                    ])->columnSpanFull(),

                Toggle::make('disponible')
                    ->label('Disponible')
                    ->default(true)
                    ->rules(['boolean'])
                    ->validationMessages([
                        'boolean' => 'El valor debe ser verdadero o falso.',
                    ]),

                FileUpload::make('imagen')
                    ->required()
                    ->label('Imagen')
                    ->image()
                    ->directory('categorias')
                    ->maxFiles(1)
                    ->maxSize(5190)
                    ->preserveFilenames()
                    ->columnSpanFull()
                    ->validationMessages([
                        'maxFiles' => 'Se permite un máximo de 1 imagen.',
                        'required' => 'Debe seleccionar al menos una imagen.',
                        'image' => 'El archivo debe ser una imagen válida.',
                        'max' => 'El tamaño de la imagen no debe exceder los 5MB.',
                    ]),


                Textarea::make('descripcion')
                    ->required()
                    ->label('Descripción')
                    ->placeholder('Escribe una breve descripción...')
                    ->hint(fn ($state, $component) => ($component->getMaxLength() - strlen($state) . '/' . $component->getMaxLength() . ' caracteres restantes.'))
                    ->live()
                    ->autosize()
                    ->minLength(5)
                    ->maxlength(300)
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

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }
}
