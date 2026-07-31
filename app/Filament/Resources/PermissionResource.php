<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $slug = 'permisos';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $activeNavigationIcon = 'heroicon-s-key';
    protected static ?string $pluralModelLabel = 'Permisos';
    protected static ?string $navigationGroup = 'Usuarios';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Permiso';
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
                Section::make([
                    TextInput::make('name')
                        ->label('Nombre del rol')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->regex('/^[a-zA-Z\s:]+$/')
                        ->minLength(2)
                        ->maxLength(50)
                        ->helperText('Solo se permiten letras, espacios y dos puntos (:)')
                        ->validationMessages([
                            'required' => 'El nombre del rol es obligatorio.',
                            'unique' => 'El nombre del rol ya existe.',
                            'min' => 'El nombre del rol debe tener al menos 2 caracteres.',
                            'max' => 'El nombre del rol no puede tener más de 50 caracteres.',
                            'regex' => 'El nombre del rol solo puede contener letras y espacios.',
                        ])
                        ->rule(function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $query = Permission::whereRaw('LOWER(name) = ?', [strtolower($value)]);

                                if ($record = $get('record')) {
                                    $query->where('id', '!=', $record->id); // Para no chocar contra sí mismo en edición
                                }

                                if ($query->exists()) {
                                    $fail('Ya existe un rol con este nombre.');
                                }
                            };
                        })
                        ->columns(1),
                    Placeholder::make('created_at')
                        ->label('Fecha de creación')
                        ->content(fn(?Permission $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                    Placeholder::make('updated_at')
                        ->label('Fecha de modificación')
                        ->content(fn(?Permission $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                ])->columns(3)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
