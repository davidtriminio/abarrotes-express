<?php

namespace App\Filament\Resources;
use App\Filament\Resources\MarcaResource\Pages\CreateMarca;
use App\Filament\Resources\MarcaResource\Pages\EditMarca;
use App\Filament\Resources\MarcaResource\Pages\ListMarcas;
use App\Filament\Resources\MarcaResource\Pages\ViewMarca;
use App\Models\Marca;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class MarcaResource extends Resource
{
    protected static ?string $model = Marca::class;
    protected static ?string $navigationGroup = 'Productos';
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $activeNavigationIcon = 'heroicon-s-cube';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'nombre';
    protected static ?string $slug = 'marcas';

    public static function canAccess(): bool
    {
        $slug = self::getSlug();
        $usuario = auth()->user();
        if ($usuario->hasPermissionTo('ver:' . $slug)) {
            return true;
        } else {
            return false;
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Regresar')
                ->url($this->previousUrl ?? $this->getResource()::getUrl('index'))
                ->button()
                ->icon('heroicon-o-chevron-left')
                ->color('gray'),
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
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



                Forms\Components\Toggle::make('disponible')
                    ->label('Disponible')
                    ->default(true)
                    ->rules(['boolean'])
                    ->validationMessages([
                        'boolean' => 'El valor debe ser verdadero o falso.',
                    ])
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('imagen')
                    ->required()
                    ->label('Imagen')
                    ->image()
                    ->directory('marcas')
                    ->maxFiles(1)
                    ->maxSize(5190)
                    ->preserveFilenames()
                    ->validationMessages([
                        'maxFiles' => 'Se permite un máximo de 1 imagen.',
                        'required' => 'Debe seleccionar al menos una imagen.',
                        'image' => 'El archivo debe ser una imagen válida.',
                        'max' => 'El tamaño de la imagen no debe exceder los 5MB.',
                    ])
                    ->columnSpanFull(),



                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->placeholder('Escribe una breve descripción...')
                    ->maxLength(300)
                    ->hint(fn ($state, $component) => ($component->getMaxLength() - strlen($state) . '/' . 300 . ' caracteres restantes.'))
                    ->live()
                    ->autosize()
                    ->rules([
                        'required',
                    ])
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
                    ])
                    ->columnSpan(2),

            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarcas::route('/'),
            'create' => CreateMarca::route('/create'),
            'edit' => EditMarca::route('/{record}/edit'),
            'view' => ViewMarca::route('/{record}/view')
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return MarcaResource::getUrl('view', ['record' => $record]);
    }
}
